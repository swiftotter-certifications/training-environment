<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('swiftotter_productdecorator_print_spec_order_item', 'id');
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }
}
