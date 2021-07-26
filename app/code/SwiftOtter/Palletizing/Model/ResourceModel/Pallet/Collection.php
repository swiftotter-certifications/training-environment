<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Model\ResourceModel\Pallet;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\Palletizing\Model\Pallet as PalletModel;
use SwiftOtter\Palletizing\Model\ResourceModel\Pallet as PalletResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(PalletModel::class, PalletResourceModel::class);
    }
}