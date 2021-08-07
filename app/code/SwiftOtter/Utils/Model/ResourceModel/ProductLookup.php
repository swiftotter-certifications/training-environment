<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/30/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model\ResourceModel;

use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;

class ProductLookup extends AbstractDb
{
    /** @var AttributeInterface */
    private $statusAttribute;

    /** @var AttributeRepository */
    private $attributeRepository;

    /** @var ProductResource */
    private $productResource;

    public function __construct(
        DbContext $context,
        AttributeRepository $attributeRepository,
        ProductResource $productResource,
        $connectionName = null
    ) {
        $this->attributeRepository = $attributeRepository;
        parent::__construct($context, $connectionName);
        $this->productResource = $productResource;
    }

    protected function _construct()
    {
        $this->_init('catalog_product_entity', 'entity_id');
    }

    public function getIdsForSkus(array $skus): array
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), $this->productResource->getIdFieldName());
        $select->where('sku IN (?)', $skus);

        return $this->getConnection()->fetchCol($select);
    }

    public function getIdFromSku(string $sku): int
    {
        return $this->getRowIdFromSku($sku);
    }

    public function getRowIdFromSku(string $sku): int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(),  $this->productResource->getIdFieldName());
        $select->where('sku = ?', $sku);

        return (int)$this->getConnection()->fetchOne($select);
    }

    public function getEntityIdFromSku(string $sku): int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'entity_id');
        $select->where('sku = ?', $sku);

        return (int)$this->getConnection()->fetchOne($select);
    }

    public function getAttributeSetId(string $sku): int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'attribute_set_id');
        $select->where('sku = ?', $sku);

        return (int)$this->getConnection()->fetchOne($select);
    }

    public function getSimpleSkusForConfigurableParent(string $sku)
    {
        $select = $this->getConnection()->select();
        $select->from(['main_table' => $this->getTable('catalog_product_super_link')], ['product_id']);
        $select->joinInner(
            ['product' => $this->getMainTable()],
            sprintf('main_table.parent_id = product.%s', $this->productResource->getIdFieldName()),
            'product.entity_id'
        );
        $select->where('product.entity_id = ?', $this->getEntityIdFromSku($sku));

        $values = $this->getConnection()->fetchCol($select);

        return array_map(function ($value) {
            return $this->getSkuFromEntityId((int)$value);
        }, $values);
    }



    public function getRowIdFromProductId(int $productId): int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), $this->productResource->getIdFieldName());
        $select->where('entity_id = ?', $productId);

        return (int)$this->getConnection()->fetchOne($select);
    }

    public function getSkuFromEntityId(int $entityId): string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'sku');
        $select->where('entity_id = ?', $entityId);

        return (string)$this->getConnection()->fetchOne($select);
    }

    public function getSkuFromRowId(int $rowId): string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'sku');
        $select->where($this->productResource->getIdFieldName() . ' = ?', $rowId);

        return (string)$this->getConnection()->fetchOne($select);
    }

    public function getProductType(int $entityId): string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'type_id');
        $select->where('entity_id = ?', $entityId);

        return (string)$this->getConnection()->fetchOne($select);
    }

    public function getParentIdsFor(int $childId): array
    {
        $select = $this->getConnection()->select();
        $select->from(['main_table' => $this->getTable('catalog_product_super_link')], []);
        $select->joinInner(
            ['product' => $this->getMainTable()],
            sprintf('main_table.parent_id = product.%s', $this->productResource->getIdFieldName()),
            'product.entity_id'
        );
        $select->where('product_id = ?', $childId);

        return $this->getConnection()->fetchCol($select);
    }

    public function getChildrenFor(int $entityId): array
    {
        $status = $this->getStatusAttribute();
        $select = $this->getConnection()->select();

        $idFieldName = $this->productResource->getIdFieldName();

        $select->from(['main_table' => $this->getMainTable()], []);
        $select->joinInner(
            ['link_table' => $this->getTable('catalog_product_super_link')],
            sprintf('link_table.parent_id = main_table.%s', $idFieldName),
            'product_id'
        );
        $select->joinInner(
            ['child_product' => $this->getTable('catalog_product_entity')],
            'link_table.product_id = child_product.entity_id',
            []
        );
        $select->joinInner(
            ['at_status' => $this->getTable('catalog_product_entity_' . $status->getBackendType())],
            sprintf('child_product.%s = at_status.%s AND ', $idFieldName, $idFieldName)
            . $this->getConnection()->quoteInto('at_status.attribute_id = ?', $status->getAttributeId()),
            []
        );

        $select->where('main_table.entity_id = ?', $entityId);
        $select->where('at_status.value = ?', 1);

        return $this->getConnection()->fetchCol($select);
    }

    public function getAttributeIdFor(int $rowId, string $value, string $valueType, bool $like = false): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable() . '_' . $valueType, 'attribute_id');
        $select->where($this->productResource->getIdFieldName() . '.row_id = ?', $rowId);

        if ($like) {
            $select->where('value LIKE ?', '%' . $value . '%');
        } else {
            $select->where('value = ?', $value);
        }

        $value = $this->getConnection()->fetchOne($select);

        return $value ? (int)$value : null;
    }

    public function getStatusAttribute(): AttributeInterface
    {
        if ($this->statusAttribute) {
            return $this->statusAttribute;
        }

        $this->statusAttribute = $this->attributeRepository->get(ProductResource::ENTITY, 'status');
        return $this->statusAttribute;
    }

    public function getAttributeCodeById(int $id): string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getTable('eav_attribute'), 'attribute_code');
        $select->where('attribute_id = ?', $id);

        return (string)$this->getConnection()->fetchOne($select);
    }

    public function saveAttribute(string $sku, string $attributeCode, $value)
    {
        $attribute = $this->attributeRepository->get(ProductModel::ENTITY, $attributeCode);

        $this->getConnection()->delete(
            $this->getAttributeTableFor($attribute),
            $this->getConnection()->quoteInto(
                $this->productResource->getIdFieldName() . ' IN (?)',
                $this->getRowIdFromSku($sku)
            )
            . ' AND '
            . $this->getConnection()->quoteInto('attribute_id = ?', $attribute->getAttributeId())
        );

        $this->getConnection()->insert(
            $this->getAttributeTableFor($attribute),
            [
                $this->productResource->getIdFieldName() => $this->getRowIdFromSku($sku),
                'attribute_id' => $attribute->getAttributeId(),
                'store_id' => 0,
                'value' => $value
            ]
        );
    }

    private function getAttributeTableFor(AttributeInterface $attribute)
    {
        return $this->getMainTable() . '_' . $attribute->getBackendType();
    }
}
