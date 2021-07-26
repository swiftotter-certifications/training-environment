<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface GiftCardUsageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface[]
     */
    public function getItems();

    /**
     * @param \SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface[] $items
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface
     */
    public function setItems(array $items);
}