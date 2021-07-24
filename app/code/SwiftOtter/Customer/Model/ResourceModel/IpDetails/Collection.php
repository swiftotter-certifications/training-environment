<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2017/12/18
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Model\ResourceModel\IpDetails;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\Customer\Model\IpDetails as IpDetailsModel;
use SwiftOtter\Customer\Model\ResourceModel\IpDetails as IpDetailsResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'swiftotter_ipdetails_collection';
    protected $_eventObject = 'ipdetails_collection';

    protected function _construct()
    {
        $this->_init(IpDetailsModel::class, IpDetailsResource::class);
    }
}
