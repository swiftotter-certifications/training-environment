<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OrderExportDetailsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get details list.
     *
     * @return \SwiftOtter\OrderExport\Api\Data\OrderExportDetailsInterface[]
     */
    public function getItems();

    /**
     * Set details list.
     *
     * @param [] $items
     * @return $this
     */
    public function setItems(array $items);
}