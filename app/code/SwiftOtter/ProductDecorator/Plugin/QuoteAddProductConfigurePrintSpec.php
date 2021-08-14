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
use SwiftOtter\ProductDecorator\Action\FindQuoteItemsChildItems;
use SwiftOtter\ProductDecorator\Action\HydratePriceRequestFromJson;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PriceRequestToPrintSpec;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as QuoteItemFactory;

class QuoteAddProductConfigurePrintSpec
{
    /** @var FindQuoteItemsChildItems */
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
        FindQuoteItemsChildItems $findQuoteItemsChildItems,
        HydratePriceRequestFromJson $hydratePriceRequestFromJson,
        PriceRequestToPrintSpec $priceRequestToPrintSpec,
        CalculatePrice $calculatePrice,
        QuoteItemFactory $printSpecQuoteItemFactory,
        PrintSpecToPriceRequest $printSpecToPriceRequest
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

        $allItems = array_merge([$quoteItem], $this->findQuoteItemsChildItems->execute($quote, $quoteItem));
        $priceRequest = $this->printSpecToPriceRequest->execute($printSpecId, $allItems);

        $price = $this->calculatePrice->execute($priceRequest);

        /** @var Quote\Item $item */
        foreach ($allItems as $item) {
            $item->setCustomPrice($price->getUnitPrice());
            $item->setOriginalCustomPrice($price->getUnitPrice());

            $printSpecQuoteItem = $item->getExtensionAttributes()->getPrintSpecQuoteItem() ?: $this->printSpecQuoteItemFactory->create();
            $printSpecQuoteItem->setPrintSpecId($printSpecId);
            $item->getExtensionAttributes()->setPrintSpecQuoteItem($printSpecQuoteItem);
        }

        return $quoteItem;
    }
}
