<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PalletQuoteItem extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('pallet_quote_item', 'id');
    }

    public function getPalletsForQuote(int $quoteId): array
    {

    }

    public function getQuoteItemsForPallet(int $palletId): array
    {

    }

    public function addQuoteItemToPallet(int $palletId, int $quoteItemId): void
    {

    }
}