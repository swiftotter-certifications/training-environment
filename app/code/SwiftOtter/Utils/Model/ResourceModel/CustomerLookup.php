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

class CustomerLookup extends AbstractDb
{
    /** @var AttributeRepository */
    private $attributeRepository;

    private $attributeMap = [];

    public function __construct(
        AttributeRepository $attributeRepository,
        DbContext $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->attributeRepository = $attributeRepository;
    }

    protected function _construct()
    {
        $this->_init('customer_entity', 'entity_id');
    }

    public function locateCustomer(string $email): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'entity_id');
        $select->where('email = ?', $email);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (int)$value : null;
    }

    public function getCustomerGroupId(int $entityId): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'group_id');
        $select->where('entity_id = ?', $entityId);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (int)$value : null;
    }

    public function getRawAttributeValue(int $customerId, string $attributeCode)
    {
        $attribute = $this->getAttribute($attributeCode);
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable() . '_' . $attribute->getBackendType(), ['value']);
        $select->where('entity_id = ?', $customerId);
        $select->where('attribute_id = ?', $attribute->getAttributeId());

        return $this->getConnection()->fetchOne($select);
    }

    private function getAttribute(string $attributeCode): AttributeInterface
    {
        if (isset($this->attributeMap[$attributeCode])) {
            return $this->attributeMap[$attributeCode];
        }

        $this->attributeMap[$attributeCode] = $this->attributeRepository->get(Customer::ENTITY, $attributeCode);
        return $this->attributeMap[$attributeCode];
    }

    public function customerExists(?int $id): bool
    {
        if (!$id) {
            return false;
        }

        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'entity_id');
        $select->where('entity_id = ?', $id);

        return (bool)$this->getConnection()->fetchOne($select);
    }

    public function getCompanyForCustomer(int $customerId): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getTable('company_advanced_customer_entity'), 'company_id');
        $select->where('customer_id = ?', $customerId);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (int)$value : null;
    }

    public function companyExists(?int $id): bool
    {
        if (!$id) {
            return false;
        }

        $select = $this->getConnection()->select();
        $select->from($this->getTable('company'), 'entity_id');
        $select->where('entity_id = ?', $id);

        return (bool)$this->getConnection()->fetchOne($select);
    }
}
