<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PrintChargeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Print charges list.
     *
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface[]
     */
    public function getItems();

    /**
     * Set Print charges list.
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
