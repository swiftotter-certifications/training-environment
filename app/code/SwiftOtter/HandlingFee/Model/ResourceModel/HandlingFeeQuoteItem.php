<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HandlingFeeQuoteItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('handling_fee_quote_item', 'id');
    }

    public function getPalletsForHandlingFee(int $quoteId): array
    {

    }

    public function getQuoteItemsForHandlingFee(int $handlingFee): array
    {

    }

    public function addQuoteItemToHandlingFee(int $handlingFee, int $quoteItemId): void
    {

    }
}
