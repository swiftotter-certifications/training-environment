<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SwiftOtter\PageBuilder\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\PageBuilder\Setup\UpgradeContentHelper;
use SwiftOtter\PageBuilder\Setup\Converters\DoNothingSampleConverter;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class RunPageBuilderConverters implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private UpgradeContentHelper $upgradeContentHelper;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        UpgradeContentHelper $upgradeContentHelper
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->upgradeContentHelper = $upgradeContentHelper;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->upgradeContentHelper->upgrade([
            DoNothingSampleConverter::class
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
