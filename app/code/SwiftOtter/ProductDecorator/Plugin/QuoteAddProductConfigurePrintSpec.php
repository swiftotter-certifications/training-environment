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
use SwiftOtter\ProductDecorator\Action\FindQuoteItemsChildItems;

class QuoteAddProductConfigurePrintSpec
{
    /** @var FindQuoteItemsChildItems */
    private $findQuoteItemsChildItems;

    public function __construct(FindQuoteItemsChildItems $findQuoteItemsChildItems)
    {
        $this->findQuoteItemsChildItems = $findQuoteItemsChildItems;
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


        return $quoteItem;
    }
}
