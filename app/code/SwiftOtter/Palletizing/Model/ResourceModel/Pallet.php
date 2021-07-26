<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Pallet extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('pallet', 'id');
    }

    public function getPalletsByCartId(int $cartId): array
    {
        $select = $this->getConnection()->select();
        $select->from('quote_item', []);
        $select->joinInner(
            'pallet_quote_item',
            'pallet_quote_item.quote_item_id = quote_item.item_id',
            []
        );
        $select->joinInner(
            'pallet',
            'pallet.id = pallet_quote_item.pallet_id',
            ['id']
        );

        $select->where('quote_item.quote_id = ?', $cartId);

        return array_map('intval', $this->getConnection()->fetchCol($select));
    }

    public function deleteByIds(array $ids): void
    {
        $this->getConnection()->delete(
            'pallet',
            $this->getConnection()->quoteInto('id IN (?)', $ids)
        );
    }
}