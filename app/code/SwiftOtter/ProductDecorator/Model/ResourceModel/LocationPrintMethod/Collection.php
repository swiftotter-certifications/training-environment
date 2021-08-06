<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/30/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod;

use SwiftOtter\ProductDecorator\Model\LocationPrintMethod as LocationPrintMethodModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(LocationPrintMethodModel::class, LocationPrintMethodResourceModel::class);
    }
}
