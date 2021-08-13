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
use SwiftOtter\ProductDecorator\Action\PriceRequestToPrintSpec;
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

    public function __construct(
        FindQuoteItemsChildItems $findQuoteItemsChildItems,
        HydratePriceRequestFromJson $hydratePriceRequestFromJson,
        PriceRequestToPrintSpec $priceRequestToPrintSpec,
        CalculatePrice $calculatePrice,
        QuoteItemFactory $printSpecQuoteItemFactory
    ) {
        $this->findQuoteItemsChildItems = $findQuoteItemsChildItems;
        $this->hydratePriceRequestFromJsonRequest = $hydratePriceRequestFromJson;
        $this->calculatePrice = $calculatePrice;
        $this->priceRequestToPrintSpec = $priceRequestToPrintSpec;
        $this->printSpecQuoteItemFactory = $printSpecQuoteItemFactory;
    }

    public function afterAddProduct(Quote $quote, Quote\Item $quoteItem, ProductInterface $product, DataObject $dataObject)
    {
        if (!$dataObject->getData('decorator')) {
            return $quoteItem;
        }

        try {
            $details = json_decode($dataObject->getData('decorator'), true);
        } catch (\Exception $ex) {
            return $quoteItem;
        }

        $allItems = $this->findQuoteItemsChildItems->execute($quote, $quoteItem);
        $priceRequest = $this->hydratePriceRequestFromJsonRequest->execute($details);

        $price = $this->calculatePrice->execute($priceRequest);

        /** @var Quote\Item $item */
        foreach ($allItems as $item) {
            $item->setCustomPrice($price->getUnitPrice());
            $item->setOriginalCustomPrice($price->getUnitPrice());
        }

        $printSpec = $this->priceRequestToPrintSpec->execute($priceRequest);
        $request = $quoteItem->getBuyRequest();
        $request->setData('print_spec_id', $printSpec->getId());

        $printSpecQuoteItem = $this->printSpecQuoteItemFactory->create();



        return $quoteItem;
    }
}
