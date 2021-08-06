<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LocationPrintMethodSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get LocationPrintMethod list.
     *
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface[]
     */
    public function getItems();

    /**
     * Set LocationPrintMethod list.
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
