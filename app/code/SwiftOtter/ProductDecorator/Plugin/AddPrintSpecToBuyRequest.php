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
use SwiftOtter\ProductDecorator\Action\CalculatePrice;
use SwiftOtter\ProductDecorator\Action\FindQuoteItemsChildItems;
use SwiftOtter\ProductDecorator\Action\HydratePriceRequestFromJson;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PriceRequestToPrintSpec;
use SwiftOtter\ProductDecorator\Attributes;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as QuoteItemFactory;

class AddPrintSpecToBuyRequest
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

        $priceRequest = $this->hydratePriceRequestFromJsonRequest->execute($details);
        $printSpec = $this->priceRequestToPrintSpec->execute($priceRequest);

        $buyRequest->setData('print_spec_id', $printSpec->getId());
        $product->addCustomOption(Attributes::OPTION_PRINT_SPEC_ID, $printSpec->getId());

        return null;
    }
}
