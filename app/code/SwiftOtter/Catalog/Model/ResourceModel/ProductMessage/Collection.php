<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model\ResourceModel\ProductMessage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\Catalog\Model\ProductMessage as ProductMessageModel;
use SwiftOtter\Catalog\Model\ResourceModel\ProductMessage as ProductMessageResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        return $this->_init(ProductMessageModel::class, ProductMessageResource::class);
    }

}
