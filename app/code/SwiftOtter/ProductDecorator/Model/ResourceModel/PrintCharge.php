<?php
declare(strict_types=1);

/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel;

use Magento\Framework\DB\Sql\Expression as SqlExpression;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PrintCharge extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('swiftotter_productdecorator_printcharge', 'id');
    }

    public function getMaxColorsByTier(string $priceType): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['printcharge' => $this->getMainTable()], [])
            ->joinInner(['tier' => 'swiftotter_productdecorator_tier'], 'tier.id = printcharge.tier_id', [])
            ->columns(['tier_id' => 'tier_id', 'max_colors' => 'MAX(colors)', 'min_tier' => 'tier.min_tier'])
            ->where('price_type = ?', $priceType)
            ->group('tier_id');

        return $connection->fetchAll($select);
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }

    public function lookup(int $tierId, int $colors, string $priceType): float
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), ['price', 'colors'])
            ->where('tier_id = ?', $tierId)
            ->where('price_type = ?', $priceType)
            ->order('colors DESC');

        $values = $connection->fetchAll($select);

        $values = array_filter($values, function ($value) use ($colors) {
            return !isset($value['colors']) || (int)$value['colors'] === $colors || !$value['colors'];
        });

        if (!count($values)) {
            return 0;
        }

        $first = reset($values);
        if (!isset($first['price'])) {
            return 0;
        }

        return (float)$first['price'];
    }

    public function getTierIdFor(int $id): int
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), ['tier_id'])
            ->where('id = ?', $id);

        return (int)$connection->fetchOne($select);
    }
}
