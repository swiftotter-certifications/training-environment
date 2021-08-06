<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\ResourceModel\Tier;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\ProductDecorator\Model\Tier as TierModel;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier as TierResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(TierModel::class, TierResourceModel::class);
    }
}
