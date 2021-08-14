<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Model\Product as ProductModel;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Attributes;

class HandleDuplicateProductAdditions
{
    public function afterRepresentProduct(QuoteItem $quoteItem, bool $result, ProductModel $product): bool
    {
        $quoteItemPrintSpecId = null;
        if ($quoteItem->getCustomAttribute(Attributes::OPTION_PRINT_SPEC_ID)
            && $quoteItem->getCustomAttribute(Attributes::OPTION_PRINT_SPEC_ID)->getValue()) {
            $quoteItemPrintSpecId = $quoteItem->getCustomAttribute(Attributes::OPTION_PRINT_SPEC_ID)->getValue();
        }

        if (!$quoteItemPrintSpecId
            && $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()
            && $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()->getPrintSpecId()) {
            $quoteItemPrintSpecId = $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()->getPrintSpecId();
        }

        $productPrintSpecId = null;
        if ($product->getCustomOption(Attributes::OPTION_PRINT_SPEC_ID)
            && $product->getCustomOption(Attributes::OPTION_PRINT_SPEC_ID)->getValue()) {
            $productPrintSpecId = $product->getCustomOption(Attributes::OPTION_PRINT_SPEC_ID)->getValue();
        }

        return $result && $productPrintSpecId === $quoteItemPrintSpecId;
    }
}
