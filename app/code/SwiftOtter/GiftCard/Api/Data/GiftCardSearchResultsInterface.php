<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface GiftCardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[]
     */
    public function getItems();

    /**
     * @param \SwiftOtter\GiftCard\Api\Data\GiftCardInterface[] $items
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface
     */
    public function setItems(array $items);
}