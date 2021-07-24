<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/4/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;

class ShareMakesDifferentQuoteItems
{
    public function afterRepresentProduct(QuoteItem $quoteItem, $output, $product)
    {
        return $output;
    }
}
