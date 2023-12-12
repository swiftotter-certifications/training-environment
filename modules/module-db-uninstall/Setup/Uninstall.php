<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc.
 **/

namespace SwiftOtter\DbUninstall\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use SwiftOtter\DbUninstall\Constants;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()->dropTable(Constants::UNINSTALL_TABLE);
    }
}
