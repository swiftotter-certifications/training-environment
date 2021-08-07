<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PrintSpecSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get print specs list.
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface[]
     */
    public function getItems();

    /**
     * Set print specs list.
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
