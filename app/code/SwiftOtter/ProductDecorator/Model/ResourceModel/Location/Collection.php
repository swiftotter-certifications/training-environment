<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\Location;

use SwiftOtter\ProductDecorator\Model\Location as LocationModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(LocationModel::class, LocationResourceModel::class);
    }
}
