<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFee;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\HandlingFee\Model\HandlingFee as HandlingFeeModel;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFee as HandlingFeeResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(HandlingFeeModel::class, HandlingFeeResourceModel::class);
    }
}
