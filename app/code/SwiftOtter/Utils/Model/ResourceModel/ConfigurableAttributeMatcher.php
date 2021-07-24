<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 4/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DbContext;

class ConfigurableAttributeMatcher extends AbstractDb
{
    /** @var ProductResource */
    private $productResource;

    /** @var AttributeRepository */
    private $attributeRepository;

    protected function _construct()
    {
        $this->_init('catalog_product_entity', 'entity_id');
    }

    public function __construct(DbContext $context, AttributeRepository $attributeRepository, ProductResource $productResource, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->productResource = $productResource;
        $this->attributeRepository = $attributeRepository;
    }

    public function findMatchingAttributes(int $childId, int $parentId)
    {
        $attributeIds = $this->getConfigurableAttributeIds($parentId);

        $output = [];
        foreach ($attributeIds as $attributeId) {
            $output[$attributeId] = $this->productResource->getAttributeRawValue($childId, $attributeId, 0);
        }

        $output = array_filter($output);

        return $output;
    }

    private function getConfigurableAttributeIds(int $parentId): array
    {
        $select = $this->getConnection()->select();
        $select->from(['product' => 'catalog_product_entity'], []);
        $select->joinInner(['attribute' => 'catalog_product_super_attribute'], 'attribute.product_id = product.' . $this->productResource->getLinkField(), 'attribute_id');
        $select->where('product.entity_id = ?', $parentId);

        return $this->getConnection()->fetchCol($select);
    }
}
