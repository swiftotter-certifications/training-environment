<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PrintMethod extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('swiftotter_productdecorator_print_method', 'id');
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }

    public function getPriceType(int $id): string
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['price_type'])
            ->where('id = ?', $id);

        $result = $connection->fetchOne($select);

        return is_bool($result) ? '' : $result;
    }

    public function getPrintMethodDataPerSkus(array $skus = []): array
    {
        $select = $this->getConnection()->select()
            ->from(['main_table' => $this->getMainTable()], [])
            ->columns(['name', 'id', 'price_type'])
            ->distinct(true)
            ->joinInner(
                ['location_print_method' => $this->getTable('swiftotter_productdecorator_location_printmethod')],
                'location_print_method.print_method_id = main_table.id',
                []
            );

        if (count($skus)) {
            $select->where('location_print_method.sku IN (?)', $skus);
        }

        return array_map(function($row) {
            $row['id'] = (int)$row['id'];
            return $row;
        }, $this->getConnection()->fetchAll($select));
    }

    public function getPrintMethodNameFor(int $printMethodId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['name'])
            ->where('id = ?', $printMethodId);

        return $connection->fetchOne($select);
    }
}
