<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod;

use SwiftOtter\ProductDecorator\Model\PrintMethod as PrintMethodModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(PrintMethodModel::class, PrintMethodResourceModel::class);
    }
}
