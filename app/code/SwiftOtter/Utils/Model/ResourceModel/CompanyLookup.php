<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model\ResourceModel;

use Magento\Customer\Model\Customer;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;

class CompanyLookup extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('company', 'entity_id');
    }
}
