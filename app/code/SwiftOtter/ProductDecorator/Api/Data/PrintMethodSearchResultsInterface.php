<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PrintMethodSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get print methods list.
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface[]
     */
    public function getItems();

    /**
     * Set print methods list.
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
