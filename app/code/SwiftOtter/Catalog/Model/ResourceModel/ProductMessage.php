<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductMessage extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('catalog_product_message', 'id');
    }

    public function getMessageFor(string $sku, string $country): ?string
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), ['message']);
        $select->where('FIND_IN_SET(?, country_id) OR country_id IS NULL OR country_id = ""', $country);
        $select->where('sku = ?', $sku);

        $value = $this->getConnection()->fetchOne($select);

        return $value ? (string)$value : null;
    }
}
