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

//    TODO: Build this out
//    public function locateCompany(?string $email, ?string $firstname, ?string $lastname): ?int
//    {
//        $select = $this->getConnection()->select();
//        $select->from($this->getMainTable(), 'entity_id');
//    }

    public function getRawValue(int $companyId, string $fieldCode)
    {
        $select = $this->getConnection()->select();

        $select->from($this->getMainTable());
        $select->joinLeft('grandstand_company', 'company_id = entity_id');
        $select->where('entity_id = ?', $companyId);

        $row = $this->getConnection()->fetchRow($select);

        return $row[$fieldCode] ?? null;
    }

//    TODO: Build this out
//    public function companyExists(?int $id): bool
//    {
//        if (!$id) {
//            return false;
//        }
//
//        $select = $this->getConnection()->select();
//        $select->from($this->getMainTable(), 'entity_id');
//        $select->where('entity_id = ?', $id);
//
//        return (bool)$this->getConnection()->fetchOne($select);
//    }
}
