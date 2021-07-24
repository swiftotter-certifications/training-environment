<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderUserInformation extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('sales_order_user_information', 'id');
    }
}