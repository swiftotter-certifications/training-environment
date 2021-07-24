<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Endpoint;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote\AddressFactory;
use Magento\Quote\Model\QuoteRepository;
use SwiftOtter\Catalog\Service\Product as ProductService;
use SwiftOtter\Checkout\Action\BuildBuyRequestFromDetails;
use SwiftOtter\Checkout\Api\AddToCartInterface;
use SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface;
use SwiftOtter\Customer\Action\SetCurrencyCodeOnQuote;
use SwiftOtter\Customer\Service\CustomerCountry;
use SwiftOtter\DownloadProduct\Api\Data\ProductDetailInterface;
use SwiftOtter\DownloadProduct\Model\ProductDetailFactory;

class AddToCart implements AddToCartInterface
{
    /** @var Session */
    private $checkoutSession;

    /** @var QuoteRepository */
    private $quoteRepository;

    /** @var ProductService */
    private $productService;

    /** @var AddressFactory */
    private $addressFactory;

    /** @var BuildBuyRequestFromDetails */
    private $buildBuyRequestFromDetails;

    /** @var ProductDetailFactory */
    private $productDetailFactory;

    /** @var CustomerCountry */
    private $customerCountry;

    /** @var SetCurrencyCodeOnQuote */
    private $setCurrencyCodeOnQuote;

    public function __construct(
        Session $checkoutSession,
        QuoteRepository $quoteRepository,
        ProductService $productService,
        AddressFactory $addressFactory,
        BuildBuyRequestFromDetails $buildBuyRequestFromDetails,
        ProductDetailFactory $productDetailFactory,
        CustomerCountry $customerCountry,
        SetCurrencyCodeOnQuote $setCurrencyCodeOnQuote
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->productService = $productService;
        $this->addressFactory = $addressFactory;
        $this->buildBuyRequestFromDetails = $buildBuyRequestFromDetails;
        $this->productDetailFactory = $productDetailFactory;
        $this->customerCountry = $customerCountry;
        $this->setCurrencyCodeOnQuote = $setCurrencyCodeOnQuote;
    }

    public function execute(array $details, string $countryId): array
    {
        $this->customerCountry->set($countryId);

        $quote = $this->checkoutSession->getQuote();
        if (!$quote->getId()) {
            $this->quoteRepository->save($quote);
            $this->checkoutSession->setQuoteId((int)$quote->getId());
        }

        $this->setCurrencyCodeOnQuote->execute($quote, $countryId);

        foreach ($details as $detail) {
            $this->addToCart($detail);
        }

        $quote->setBillingAddress($this->addressFactory->create());
        $quote->setShippingAddress($this->addressFactory->create());
        $quote->getShippingAddress()->setCollectShippingRates(true);

        $quote->setItems([]);

        $quote->setTotalsCollectedFlag(false);
        $quote->setTriggerRecollect(1);
        $quote->collectTotals();

        $this->quoteRepository->save($quote);

        $output = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $output[] = $this->productDetailFactory->create([
                'product' => $item->getProduct(),
                'qty' => $item->getQty(),
                'type' => ProductDetailInterface::DISPLAY_TYPE_PRODUCT,
                'country' => $this->customerCountry->get()
            ]);
        }

        return $output;
    }

    private function addToCart(AddToCartDetailsInterface $details): array
    {
        $product = $this->productService->get($details->getSku());
        $share = [];
        if ($details->getShare()
            && $details->getShare()->getEnabled()) {
            $share = [
                'enabled' => true,
                'send' => $details->getShare()->getSend(),
                'email' => $details->getShare()->getEmail()
            ];
        }

        $product->addCustomOption('share', json_encode($share));

        $quote = $this->checkoutSession->getQuote();
        $quoteItem = $quote->addProduct(
            $product,
            new DataObject($this->buildBuyRequestFromDetails->execute($details))
        );

        $output = [$quoteItem];
        foreach ($details->getChildren() as $child) {
            $output = array_merge($output, $this->addToCart($child));
        }

        return $output;
    }
}
