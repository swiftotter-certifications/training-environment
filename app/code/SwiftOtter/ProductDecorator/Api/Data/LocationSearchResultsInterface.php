<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LocationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get locations list.
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationInterface[]
     */
    public function getItems();

    /**
     * Set locations list.
     * @param \SwiftOtter\ProductDecorator\Api\Data\LocationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
