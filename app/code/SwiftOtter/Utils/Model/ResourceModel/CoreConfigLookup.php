<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CoreConfigLookup extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('core_config_data', 'config_id');
    }

    public function getValuesLike(string $path): array
    {
        $select = $this->getConnection()->select();
        $select->from($this->getMainTable(), 'value');
        $select->where('path LIKE "%?%"', $path);

        return $this->getConnection()->fetchCol($select);
    }
}
