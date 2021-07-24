<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/25/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Model\ResourceModel;

use Magento\Customer\Model\Customer;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;
use SwiftOtter\Customer\Attributes;

class CustomerLookup extends AbstractDb
{
    /** @var AttributeRepository */
    private $attributeRepository;

    public function __construct(DbContext $context, AttributeRepository $attributeRepository, $connectionName = null)
    {
        $this->attributeRepository = $attributeRepository;

        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('customer_entity', 'entity_id');
    }

    public function saveCountryCodeFor(int $customerId, ?string $countryCode): void
    {
        if (!$customerId) {
            return;
        }

        return; //temporarily disabled

        $billingAddressId = $this->getBillingAddressId($customerId);
        if ($billingAddressId && $countryCode) {
            $this->getConnection()->update(
                $this->getTable('customer_address_entity'),
                ['country_id' => $countryCode],
                $this->getConnection()->quoteInto('entity_id', $billingAddressId)
            );
        }

        $countryAttribute = $this->getCountryAttribute();
        $this->getConnection()->delete(
            $this->getTable('customer_entity_' . $countryAttribute->getBackendType()),
            implode(' AND ', [
                $this->getConnection()->quoteInto('attribute_id = ?', $countryAttribute->getAttributeId()),
                $this->getConnection()->quoteInto('entity_id = ?', $customerId)
            ])
        );

        $this->getConnection()->insert(
            $this->getTable('customer_entity_' . $countryAttribute->getBackendType()),
            [
                'entity_id' => $customerId,
                'attribute_id' => $countryAttribute->getAttributeId(),
                'value' => $countryCode
            ]
        );
    }



    public function getCountryFor(int $customerId): ?string
    {
        if (!$customerId) {
            return null;
        }

        $billingAddressId = $this->getBillingAddressId($customerId);
        if (!$billingAddressId) {
            return null;
        }

        $select = $this->getConnection()->select();
        $select->from('customer_address_entity', 'country_id');
        $select->where('entity_id = ?', $billingAddressId);

        $value = $this->getConnection()->fetchOne($select);
        if ($value) {
            return $value;
        }

        $countryAttribute = $this->getCountryAttribute();
        $select = $this->getConnection()->select();
        $select->from('customer_entity_' . $countryAttribute->getBackendType(), 'value');
        $select->where('entity_id = ?', $customerId);
        $select->where('attribute_id = ?', $countryAttribute);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (string)$value : $value;
    }

    public function getBillingAddressId(int $customerId): ?int
    {
        $select = $this->getConnection()->select();
        $select->from('customer_entity', 'default_billing');
        $select->where('entity_id = ?', $customerId);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (int)$value : null;
    }

    public function getCountryIdForAddress(int $quoteId, ?string $addressType = ''): ?string
    {
        $select = $this->getConnection()->select();
        $select->from('quote_address', 'country_id');
        $select->where('quote_id = ?', $quoteId);
        $select->where('country_id != null');

        if ($addressType) {
            $select->where('address_type = ?', $addressType);
        }

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (string)$value : null;
    }

    public function getCountryAttribute(): AttributeInterface
    {
        return $this->attributeRepository->get(Customer::ENTITY, Attributes::COUNTRY);
    }
}
