<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\Utils\Model\UnifiedSale;
use Swiftotter\Utils\Model\UnifiedSale\Item as UnifiedSaleItem;

class FindItemsChildItems
{
    public function execute(UnifiedSale $unifiedSale, UnifiedSaleItem $unifiedSaleItem)
    {
        return array_filter($unifiedSale->getAllItems(), function(UnifiedSaleItem $potentialChild) use ($unifiedSaleItem): bool {
            if ($unifiedSaleItem->getId()
                && $potentialChild->getParentItemId() == $unifiedSaleItem->getId()) {
                return true;
            }

            if ($potentialChild->getParentItem() === $unifiedSaleItem) {
                return true;
            }

            return false;
        });
    }
}
