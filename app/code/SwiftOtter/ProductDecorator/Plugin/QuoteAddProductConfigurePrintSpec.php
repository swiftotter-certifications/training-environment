<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;
use SwiftOtter\ProductDecorator\Action\CalculatePrice;
use SwiftOtter\ProductDecorator\Action\FindItemsChildItems;
use SwiftOtter\ProductDecorator\Action\HydratePriceRequestFromJson;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PriceRequestToPrintSpec;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as QuoteItemFactory;
use SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface;

class QuoteAddProductConfigurePrintSpec
{
    /** @var FindItemsChildItems */
    private $findQuoteItemsChildItems;

    /** @var HydratePriceRequestFromJson */
    private $hydratePriceRequestFromJsonRequest;

    /** @var CalculatePrice */
    private $calculatePrice;

    /** @var PriceRequestToPrintSpec */
    private $priceRequestToPrintSpec;

    /** @var QuoteItemFactory */
    private $printSpecQuoteItemFactory;

    /** @var PrintSpecToPriceRequest */
    private $printSpecToPriceRequest;

    public function __construct(
        FindItemsChildItems         $findQuoteItemsChildItems,
        HydratePriceRequestFromJson $hydratePriceRequestFromJson,
        PriceRequestToPrintSpec     $priceRequestToPrintSpec,
        CalculatePrice              $calculatePrice,
        QuoteItemFactory            $printSpecQuoteItemFactory,
        PrintSpecToPriceRequest     $printSpecToPriceRequest
    ) {
        $this->findQuoteItemsChildItems = $findQuoteItemsChildItems;
        $this->hydratePriceRequestFromJsonRequest = $hydratePriceRequestFromJson;
        $this->calculatePrice = $calculatePrice;
        $this->priceRequestToPrintSpec = $priceRequestToPrintSpec;
        $this->printSpecQuoteItemFactory = $printSpecQuoteItemFactory;
        $this->printSpecToPriceRequest = $printSpecToPriceRequest;
    }

    public function afterAddProduct(Quote $quote, Quote\Item $quoteItem, ProductInterface $product, $dataObject)
    {
        if (!$quoteItem->getOptionByCode('print_spec_id')
            || !$quoteItem->getOptionByCode('print_spec_id')->getValue()) {
            return $quoteItem;
        }

        $printSpecId = $quoteItem->getOptionByCode('print_spec_id')->getValue();

        $allItems = array_merge(
            [$quoteItem->getExtensionAttributes()->getUnified()],
            $this->findQuoteItemsChildItems->execute(
                $quote->getExtensionAttributes()->getUnified(),
                $quoteItem->getExtensionAttributes()->getUnified()
            ));

        $priceRequest = $this->printSpecToPriceRequest->execute($printSpecId, $allItems);

        $price = $this->calculatePrice->execute($priceRequest);

        /** @var UnifiedSaleItemInterface $item */
        foreach ($allItems as $item) {
            $item->setCustomPrice($price->getUnitPrice());
            $item->setOriginalCustomPrice($price->getUnitPrice());

            $printSpecQuoteItem = $item->getExtensionAttributes()->getPrintSpecItem() ?: $this->printSpecQuoteItemFactory->create();
            $printSpecQuoteItem->setPrintSpecId($printSpecId);
            $item->getExtensionAttributes()->setPrintSpecItem($printSpecQuoteItem);
        }

        return $quoteItem;
    }
}
