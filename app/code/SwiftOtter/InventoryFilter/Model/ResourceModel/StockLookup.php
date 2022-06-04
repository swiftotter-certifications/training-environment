<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/13/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\InventoryFilter\Model\ResourceModel;

use Magento\Framework\DB\Sql\Expression;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use SwiftOtter\InventoryFilter\Plugin\RebuildStockQuantityLookups;

class StockLookup extends AbstractDb
{
    /** @var null|array<int, string> */
    private ?array $stockNames = null;

    protected function _construct()
    {
        $this->_init('inventory_stock', 'stock_id');
    }

    /**
     * This method replaces:
     * \Magento\InventorySalesAdminUi\Ui\Component\Listing\Column\SalableQuantity::prepareDataSource
     *
     * The problem is the above iterates through each product and loads in all stock information. This is
     * a very common performance complaint in the admin panel. By going directly to the database with ONE
     * query, we reduce the number of calls (perhaps a 95% improvement in speed).
     *
     * The only downside is we aren't specifically tracking "Manage Inventory". I don't see this is used on
     * the frontend, so I'm use this as a way to determine in/out of stock.
     *
     * @param string[] $skus
     * @return array{sku: string} Keys are the SKUs (but SKU is also in the child object) and the child contains the
     * quantity and is_in_stock for each stock.
     */
    public function getStockDetailsFor(array $skus): array
    {
        $select = $this->getConnection()->select();
        $select->from(
            ['product' => $this->getTable('catalog_product_entity')],
            'sku'
        );

        $select->joinLeft(
            ['inventory_assigned' => 'inventory_source_item'],
            "product.sku = inventory_assigned.sku",
            [RebuildStockQuantityLookups::SOURCE_CODES_KEY => new Expression('GROUP_CONCAT(source_code)')]
        );

        $select->joinLeft(
            ['stock_configuration' => 'cataloginventory_stock_item'],
            "product.entity_id = stock_configuration.product_id",
            [
                RebuildStockQuantityLookups::CONFIG_MIN_QTY_KEY => new Expression('MAX(min_qty)'),
                RebuildStockQuantityLookups::CONFIG_USE_CONFIG_MIN_QTY_KEY => new Expression('MAX(use_config_min_qty)'),
            ]
        );

        foreach ($this->getStockIds() as $stockId) {
            if (!$this->getConnection()->isTableExists('inventory_stock_' . $stockId)) {
                continue;
            }

            $alias = RebuildStockQuantityLookups::PREFIX_STOCK . '_' . $stockId;
            $select->joinLeft(
                [$alias => 'inventory_stock_' . $stockId],
                "product.sku = {$alias}.sku",
                [
                    "{$alias}_quantity" => new Expression("SUM({$alias}.quantity)"),
                    "{$alias}_is_salable" => new Expression("IF(SUM({$alias}.is_salable) > 0, 1, 0)")
                ]
            );

            $reservationAlias = RebuildStockQuantityLookups::PREFIX_RESERVATION . "_" . $stockId;
            $select->joinLeft(
                [$reservationAlias => 'inventory_reservation'],
                implode(' ', [
                    "product.sku = {$reservationAlias}.sku",
                     'AND',
                     $this->getConnection()->quoteInto("{$reservationAlias}.stock_id = ?", $stockId)
                ]),
                [
                    "{$reservationAlias}_quantity" => new Expression("SUM({$reservationAlias}.quantity)")
                ]
            );
        }

        $select->where('product.sku IN (?)', $skus);
        $select->group('product.sku');

        return $this->getConnection()->fetchAssoc($select);
    }

    public function getSourceAssociations(): array
    {
        $select = $this->getConnection()->select();
        $select->from(
            ['link' => $this->getTable('inventory_source_stock_link')],
            ['stock_id', 'source_code']
        );

        return $this->getConnection()->fetchAssoc($select);
    }

    /**
     * @return array<int, int>
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStockIds(): array
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'stock_id');

        return $this->getConnection()->fetchCol($select);
    }

    /**
     * @return array<int, string> Stock Names indexed by Stock ID
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStockNames(): array
    {
        if ($this->stockNames) {
            return $this->stockNames;
        }

        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['stock_id', 'name']);

        $this->stockNames = $this->getConnection()->fetchPairs($select);
        return $this->stockNames;
    }
}
