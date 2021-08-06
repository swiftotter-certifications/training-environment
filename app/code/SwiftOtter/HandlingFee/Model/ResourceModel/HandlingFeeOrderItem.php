<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HandlingFeeOrderItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('handling_fee_order_item', 'id');
    }

    public function getHandlingFeesPerOrder(int $orderId): array
    {

    }

    public function getOrderItemsForHandlingFee(int $handlingFeeId): array
    {

    }

    public function addOrderItemToHandlingFee(int $handlingFeeId, int $orderItemId): void
    {

    }
}
