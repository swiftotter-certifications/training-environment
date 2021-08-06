<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\ProductDecorator\Model\PrintCharge as PrintChargeModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge as PrintChargeResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(PrintChargeModel::class, PrintChargeResourceModel::class);
    }
}
