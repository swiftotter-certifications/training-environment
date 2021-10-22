<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;
use SwiftOtter\ProductDecorator\Action\CalculatePrice;
use SwiftOtter\ProductDecorator\Action\FindItemsChildItems;
use SwiftOtter\ProductDecorator\Action\HydratePriceRequestFromJson;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PriceRequestToPrintSpec;
use SwiftOtter\ProductDecorator\Attributes;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as QuoteItemFactory;

class AddPrintSpecToBuyRequest
{
    private HydratePriceRequestFromJson $hydratePriceRequestFromJsonRequest;
    private PriceRequestToPrintSpec $priceRequestToPrintSpec;
    private QuoteRepository $quoteRepository;

    public function __construct(
        HydratePriceRequestFromJson $hydratePriceRequestFromJson,
        PriceRequestToPrintSpec $priceRequestToPrintSpec,
        QuoteRepository $quoteRepository
    ) {
        $this->hydratePriceRequestFromJsonRequest = $hydratePriceRequestFromJson;
        $this->priceRequestToPrintSpec = $priceRequestToPrintSpec;
        $this->quoteRepository = $quoteRepository;
    }

    public function beforeAddProduct(Quote $quote, Product $product, $buyRequest)
    {
        if (!is_object($buyRequest) || !$buyRequest->getData('decorator')) {
            return null;
        }

        try {
            $details = json_decode($buyRequest->getData('decorator'), true);
        } catch (\Exception $ex) {
            return null;
        }

        if (!$quote->getId()) {
            $quote->setTotalsCollectedFlag(true);
            $this->quoteRepository->save($quote);
            $quote->setTotalsCollectedFlag(false);
        }

        $priceRequest = $this->hydratePriceRequestFromJsonRequest->execute($details);
        $printSpec = $this->priceRequestToPrintSpec->execute($priceRequest, (int)$quote->getId());

        $buyRequest->setData('print_spec_id', $printSpec->getId());
        $product->addCustomOption(Attributes::OPTION_PRINT_SPEC_ID, $printSpec->getId());

        return null;
    }
}
