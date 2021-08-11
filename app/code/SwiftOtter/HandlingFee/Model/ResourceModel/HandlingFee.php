<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HandlingFee extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('handling_fee', 'id');
    }

    public function getHandlingFeesByCartId(int $cartId): array
    {
        $select = $this->getConnection()->select();
        $select->from('quote_item', []);
        $select->joinInner(
            'handling_fee_quote_item',
            'handling_fee_quote_item.quote_item_id = quote_item.item_id',
            []
        );
        $select->joinInner(
            'handling_fee',
            'handling_fee.id = handling_fee_quote_item.handling_fee_id',
            ['id']
        );

        $select->where('quote_item.quote_id = ?', $cartId);

        return array_map('intval', $this->getConnection()->fetchCol($select));
    }

    public function deleteByIds(array $ids): void
    {
        $this->getConnection()->delete(
            'handling_fee',
            $this->getConnection()->quoteInto('id IN (?)', $ids)
        );
    }
}
