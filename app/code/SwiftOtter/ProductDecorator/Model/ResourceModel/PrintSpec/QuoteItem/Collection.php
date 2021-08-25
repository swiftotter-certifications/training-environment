<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItem as PrintSpecQuoteItemModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem as PrintSpecQuoteItemResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(PrintSpecQuoteItemModel::class, PrintSpecQuoteItemResourceModel::class);
    }

    public function joinPrintSpec(): void
    {
        if (isset($this->getSelect()->getPart(Select::FROM)['print_spec'])) {
            return;
        }

        $this->getSelect()
            ->joinLeft(
                ['print_spec' => $this->getTable('swiftotter_productdecorator_print_spec')],
                'main_table.print_spec_id = print_spec.id',
                []
            );
    }

    public function filterDeletedPrintSpecs()
    {
        $this->joinPrintSpec();
        $this->getSelect()->where('print_spec.is_deleted = 0');

        return $this;
    }

    public function filterByCartId(int $cartId)
    {
        $this->joinPrintSpec();
        $this->getSelect()->where('print_spec.cart_id = ?', $cartId);

        return $this;
    }
}
