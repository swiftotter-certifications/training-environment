<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/8/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GiftCardQuote extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('gift_card_quote', 'id');
    }

    public function add(int $quoteId, $giftCardId)
    {
        if ($this->get($quoteId)) {
            $this->getConnection()->update(
                $this->getMainTable(),
                [
                    'gift_card_id' => $giftCardId
                ],
                $this->getConnection()->quoteInto('quote_id = ?', $quoteId)
            );
        } else {
            $this->getConnection()->insert(
                $this->getMainTable(),
                [
                    'quote_id' => $quoteId,
                    'gift_card_id' => $giftCardId
                ]
            );
        }
    }

    public function get(int $quoteId): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'gift_card_id');
        $select->where('quote_id = ?', $quoteId);

        $value = $this->getConnection()->fetchOne($select);
        if (!$value) {
            return null;
        }

        return (int)$value;
    }
}