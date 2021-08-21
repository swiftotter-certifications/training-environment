<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuoteItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('swiftotter_productdecorator_print_spec_quote_item', 'id');
    }

    public function getById(int $id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['id'])
            ->where('id = ?', $id);

        return $connection->fetchOne($select);
    }

    public function replace(int $printSpecId, int $quoteItemId): void
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            $this->getConnection()->quoteInto('quote_item_id = ?', $quoteItemId)
        );

        $this->getConnection()->insert(
            $this->getMainTable(),
            [
                'print_spec_id' => $printSpecId,
                'quote_item_id' => $quoteItemId
            ]
        );
    }

    public function getByQuoteItem(int $quoteItemId): int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['print_spec_id']);
        $select->where('quote_item_id = ?', $quoteItemId);
        $select->joinInner(
            ['print_spec' => $this->getTable('swiftotter_productdecorator_print_spec')],
            'print_spec.id = swiftotter_productdecorator_print_spec_quote_item.print_spec_id',
            ['print_spec_id' =>'print_spec.id']
        );
        $select->where('print_spec.is_deleted = 0');

        return (int)$this->getConnection()->fetchOne($select);
    }
}
