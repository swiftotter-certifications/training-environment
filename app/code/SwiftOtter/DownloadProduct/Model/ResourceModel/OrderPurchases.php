<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderPurchases extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sales_order_item', 'item_id');
    }

    public function getCustomerHasPurchased(int $customerId, int $productId): bool
    {
        return $this->fetchFromOrderItem($customerId, $productId)
            || $this->fetchFromDownloadable($customerId, $productId);
    }

    private function fetchFromOrderItem(int $customerId, int $productId): bool
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), $this->getIdFieldName());
        $select->joinInner(
            'sales_order',
            'sales_order.entity_id = sales_order_item.order_id',
            ''
        );

        $select->where('sales_order.customer_id = ?', $customerId);
        $select->where('sales_order_item.product_id = ?', $productId);

        return (bool)$this->getConnection()->fetchOne($select);
    }

    private function fetchFromDownloadable(int $customerId, int $productId): bool
    {
        $select = $this->getConnection()->select();
        $select->from(['download' => 'downloadable_link_purchased'], 'purchased_id');
        $select->joinInner(
            ['download_item' => 'downloadable_link_purchased_item'],
            'download_item.purchased_id = download.purchased_id',
            ''
        );

        $select->where('download.customer_id = ?', $customerId);
        $select->where('download_item.product_id = ?', $productId);

        return (bool)$this->getConnection()->fetchOne($select);
    }
}