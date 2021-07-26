<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PalletOrderItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('pallet_order_item', 'id');
    }

    public function getPalletsForOrder(int $orderId): array
    {

    }

    public function getOrderItemsForPallet(int $palletId): array
    {

    }

    public function addOrderItemToPallet(int $palletId, int $orderItemId): void
    {

    }
}