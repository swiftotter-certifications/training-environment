<?php
declare(strict_types=1);

/**
 * @by SwiftOtter, Inc. 12/13/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;
use SwiftOtter\Utils\Action\FindParentConfigurableForSimple;

class LocationPrintMethod extends AbstractDb
{
    const TABLE = 'swiftotter_productdecorator_location_printmethod';
    /** @var \SwiftOtter\Utils\Action\FindParentConfigurableForSimple */
    private $findParentConfigurableForSimple;

    public function __construct(
        DbContext $context,
        \SwiftOtter\Utils\Action\FindParentConfigurableForSimple $findParentConfigurableForSimple,
        $connectionName = null
    ) {
        $this->findParentConfigurableForSimple = $findParentConfigurableForSimple;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init(self::TABLE, 'id');
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }

    public function getAvailableLocationIds(string $sku): array
    {
        $select = $this->getConnection()->select();
        $select->from(['location_print_method' => $this->getMainTable()], 'location_id');
        $select->joinInner(
            ['location' => 'swiftotter_productdecorator_locations'],
            'location_print_method.location_id = location.id',
            ['name', 'code']
        );
        $select->where('sku = ?', $sku);
        $select->distinct(true);

        return $this->getConnection()->fetchAll($select);
    }

    public function getPrintMethodIdsGroupedByLocationIds(string $sku): array
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['location_id', 'print_method_id']);
        $select->where('sku = ?', $sku);

        $values = $this->getConnection()->fetchAll($select);
        $output = [];

        foreach ($values as $pair) {
            if (!isset($output[$pair['location_id']])) {
                $output[$pair['location_id']] = [];
            }

            $output[$pair['location_id']][] = (int)$pair['print_method_id'];
        }

        return $output;
    }

    public function getBestPrintMethodFor(string $sku, array $locationIds): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['main_table' => $this->getMainTable()], ['location_id', 'print_method_id'])
            ->joinInner(
                ['print_method' => $this->getTable('swiftotter_productdecorator_print_method')],
                'print_method.id = main_table.print_method_id',
                []
            )
            ->where('sku = ?', $sku)
            ->where('location_id IN (?)', $locationIds);

        $rows = $connection->fetchAll($select);

        if (!count($rows)) {
            return [];
        }

        $row = reset($rows);
        return [
            'location_id' => (int)$row['location_id'],
            'print_method_id' => (int)$row['print_method_id']
        ];
    }

    public function getSkusFor(int $locationId): array
    {
        $select = $this->getConnection()->select()
            ->from(['main_table' => $this->getMainTable()], ['sku'])
            ->where('location_id = ?', $locationId);

        return $this->getConnection()->fetchCol($select);
    }

    public function getPreferredLocationsIdFor(string $sku, bool $allowUpcharge = false): array
    {
        $connection = $this->getConnection();
        $select = $connection->select();
        $select->from(
            ['main_table' => $this->getTable(self::TABLE)],
            ['location_id']
        );
        $select->joinInner(
            ['location' => $this->getTable('swiftotter_productdecorator_locations')],
            'location.id = main_table.location_id',
            []
        );

        if (!$allowUpcharge) {
            $select->where('location.upcharge = 0');
        } else {
            $select->order('location.upcharge ASC');
        }

        $select->distinct(true);
        $values = $connection->fetchCol($select);

        if (!count($values) && !$allowUpcharge) {
            return $this->getPreferredLocationsIdFor($sku, true);
        }

        return array_unique($values);
    }
}
