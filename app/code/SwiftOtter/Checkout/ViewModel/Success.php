<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\ViewModel;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Pricing\Helper\Data as CurrencyFormatter;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\OrderRepository;
use SwiftOtter\Catalog\Model\ResourceModel\ProductMessage as ProductMessageResource;
use SwiftOtter\DownloadProduct\Attributes;
use SwiftOtter\DownloadProduct\Model\ProductDetail;
use SwiftOtter\DownloadProduct\Model\ProductDetailFactory;

class Success implements ArgumentInterface
{
    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var ProductResource */
    private $productResource;

    /** @var ProductMessageResource */
    private $productMessageResource;

    /** @var ProductDetailFactory */
    private $productDetailFactory;

    /** @var ProductResource\CollectionFactory */
    private $productCollectionFactory;

    /** @var HttpContext */
    private $httpContext;

    /** @var UrlInterface */
    private $url;

    /** @var CurrencyFormatter */
    private $currencyFormatter;

    public function __construct(
        CheckoutSession $checkoutSession,
        OrderRepository $orderRepository,
        ProductResource $productResource,
        ProductMessageResource $productMessageResource,
        ProductDetailFactory $productDetailFactory,
        ProductCollectionFactory $productCollectionFactory,
        HttpContext $httpContext,
        UrlInterface $url,
        CurrencyFormatter $currencyFormatter
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        $this->productResource = $productResource;
        $this->productMessageResource = $productMessageResource;
        $this->productDetailFactory = $productDetailFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->httpContext = $httpContext;
        $this->url = $url;
        $this->currencyFormatter = $currencyFormatter;
    }

    private function getOrder(): OrderInterface
    {
        return $this->checkoutSession->getLastRealOrder();
    }

    public function getPostPurchaseMessages(): array
    {
        $output = [];

        foreach ($this->getOrder()->getItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            $output[] = $this->productResource->getAttributeRawValue(
                $item->getProductId(),
                Attributes::POST_PURCHASE_MESSAGE,
                $this->getOrder()->getStoreId()
            );
        }

        return array_filter($output);
    }

    public function getMessages(): array
    {
        $output = [];

        foreach ($this->getOrder()->getItems() as $item) {
            $output[] = $this->productMessageResource->getMessageFor(
                $item->getSku(),
                $this->getOrder()->getBillingAddress()->getCountryId()
            );
        }

        return $output;
    }

    /**
     * @return \SwiftOtter\DownloadProduct\Model\ProductDetail[]
     */
    public function getOrderItems(): array
    {
        $items = array_filter($this->getOrder()->getItems(), function(OrderItemInterface $orderItem) {
            return !$orderItem->getParentItemId();
        });

        return array_map(function(OrderItemInterface $orderItem) {
            $product = $this->productCollectionFactory->create()
                ->addFieldToFilter('sku', $orderItem->getSku())
                ->addAttributeToSelect(ProductDetail::REQUIRED_ATTRIBUTES)
                ->addMediaGalleryData()
                ->getFirstItem();

            return $this->productDetailFactory->create([
                'product' => $product,
                'qty' => $orderItem->getQtyOrdered(),
                'type' => 'simple',
                'country' => $this->getOrder()->getBillingAddress()->getCountryId()
            ]);
        }, $items);
    }

    public function isLoggedIn(): bool
    {
        return (bool)$this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    public function getOrderNumber()
    {
        return $this->getOrder()->getIncrementId();
    }

    public function getViewUrl()
    {
        return $this->url->getUrl(
            'sales/order/view/',
            ['order_id' => $this->getOrder()->getEntityId()]
        );
    }

    public function getOrderTotal()
    {
        return $this->currencyFormatter->currencyByStore($this->getOrder()->getBaseGrandTotal(), $this->getOrder()->getStoreId());
    }

    public function getDownloadableUrl()
    {
        return $this->url->getUrl('downloadable/customer/products/');
    }
}
