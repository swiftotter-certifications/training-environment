<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SwiftOtter\ProductDecorator\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class CreateLocationPrintMethods implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->insertArray(
            'swiftotter_productdecorator_location_printmethod',
            ['location_id', 'print_method_id', 'sku'],
            [
                ['1', '3', 'great-tent-1'],
                ['1', '2', 'great-tent-1'],
                ['1', '5', 'great-tent-1'],
                ['2', '3', 'great-tent-1'],
                ['2', '2', 'great-tent-1'],
                ['2', '5', 'great-tent-1'],
                ['3', '3', 'great-tent-1'],
                ['3', '2', 'great-tent-1'],
                ['3', '5', 'great-tent-1'],
            ]
        );
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
        return [
            \SwiftOtter\ProductDecorator\Setup\Patch\Data\CreateLocations::class,
            \SwiftOtter\ProductDecorator\Setup\Patch\Data\CreatePrintMethods::class
        ];
    }
}
