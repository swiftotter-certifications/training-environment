<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItem as PrintSpecQuoteItemModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem as PrintSpecQuoteItemResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(PrintSpecQuoteItemModel::class, PrintSpecQuoteItemResourceModel::class);
    }
}
