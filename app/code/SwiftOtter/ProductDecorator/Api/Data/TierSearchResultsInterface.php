<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TierSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get tiers list.
     *
     * @return \SwiftOtter\ProductDecorator\Api\Data\TierInterface[]
     */
    public function getItems();

    /**
     * Set tiers list.
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\TierInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
