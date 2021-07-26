<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\GiftCard\Model\GiftCardUsage as GiftCardUsageModel;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResourceModel;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(GiftCardUsageModel::class, GiftCardUsageResourceModel::class);
    }
}