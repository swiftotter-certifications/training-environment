<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductLookup extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('catalog_product_entity', 'entity_id');
    }

    public function getIdBySku(string $sku): ?int
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'entity_id');
        $select->where('sku = ?', $sku);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (int)$value : null;
    }

    public function getSkuById(int $id): ?string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'sku');
        $select->where('entity_id = ?', $id);

        $value = $this->getConnection()->fetchOne($select);
        return $value ? (string)$value : null;
    }
}
