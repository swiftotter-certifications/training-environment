<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class FindQuoteItemsChildItems
{
    public function execute(Quote $quote, QuoteItem $quoteItem)
    {
        return array_filter($quote->getAllItems(), function(QuoteItem $potentialChild) use ($quoteItem): bool {
            if ($quoteItem->getId()
                && $potentialChild->getParentItemId() == $quoteItem->getId()) {
                return true;
            }

            if ($potentialChild->getParentItem() === $quoteItem) {
                return true;
            }

            return false;
        });
    }
}
