<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec;

use SwiftOtter\ProductDecorator\Model\PrintSpec as PrintSpecModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(PrintSpecModel::class, PrintSpecResourceModel::class);
    }
}
