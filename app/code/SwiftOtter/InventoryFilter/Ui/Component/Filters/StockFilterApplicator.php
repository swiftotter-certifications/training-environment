<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/10/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\InventoryFilter\Ui\Component\Filters;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class StockFilterApplicator implements AddFilterToCollectionInterface
{
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        $set = false;

        $stockId = $condition['eq']['stock_id'] ?? '';
        if (!$stockId
            || !$collection->getResource()->getConnection()->isTableExists('inventory_stock_' . $stockId)) {
            return;
        }

        if (!empty($condition['eq']['from'])) {
            $collection->getSelect()->where('stock.quantity >= ?', $condition['eq']['from']);
            $set = true;
        }

        if (!empty($condition['eq']['to'])) {
            $collection->getSelect()->where('stock.quantity <= ? ', $condition['eq']['to']);
            $set = true;
        }

        if (!$set) {
            return;
        }

        $collection->getSelect()->joinLeft(
            ['stock' => 'inventory_stock_' . $stockId],
            'stock.sku = e.sku',
            []
        );
    }
}
