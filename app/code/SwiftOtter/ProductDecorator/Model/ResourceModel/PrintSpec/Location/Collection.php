<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\ProductDecorator\Model\PrintSpec\Location as PrintSpecLocationModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location as PrintSpecLocationResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(PrintSpecLocationModel::class, PrintSpecLocationResourceModel::class);
    }
}
