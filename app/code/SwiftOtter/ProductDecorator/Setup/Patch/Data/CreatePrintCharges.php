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
class CreatePrintCharges implements DataPatchInterface
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
            'swiftotter_productdecorator_printcharge',
            ['tier_id', 'price', 'colors', 'price_type'],
            [
                ['1','2.076', '1', 'embroidery'],
                ['1','2.250', '2', 'embroidery'],
                ['1','2.415', '3', 'embroidery'],
                ['1','2.810', '4', 'embroidery'],
                ['2','1.870', '1', 'embroidery'],
                ['2','2.009', '2', 'embroidery'],
                ['2','2.137', '3', 'embroidery'],
                ['2','2.465', '4', 'embroidery'],
                ['3','1.685', '1', 'embroidery'],
                ['3','1.794', '2', 'embroidery'],
                ['3','1.891', '3', 'embroidery'],
                ['3','2.162', '4', 'embroidery'],
                ['4','1.518', '1', 'embroidery'],
                ['4','1.602', '2', 'embroidery'],
                ['4','1.674', '3', 'embroidery'],
                ['4','1.896', '4', 'embroidery'],
                ['5','1.368', '1', 'embroidery'],
                ['5','1.430', '2', 'embroidery'],
                ['5','1.481', '3', 'embroidery'],
                ['5','1.663', '4', 'embroidery'],
                ['6','1.232', '1', 'embroidery'],
                ['6','1.277', '2', 'embroidery'],
                ['6','1.311', '3', 'embroidery'],
                ['6','1.459', '4', 'embroidery'],
                ['7','1.110', '1', 'embroidery'],
                ['7','1.140', '2', 'embroidery'],
                ['7','1.160', '3', 'embroidery'],
                ['7','1.280', '4', 'embroidery'],
                ['1','3.572', '1', 'paint'],
                ['1','4.954', '2', 'paint'],
                ['1','5.934', '3', 'paint'],
                ['1','6.804', '4', 'paint'],
                ['2','3.218', '1', 'paint'],
                ['2','4.423', '2', 'paint'],
                ['2','5.251', '3', 'paint'],
                ['2','5.969', '4', 'paint'],
                ['3','2.900', '1', 'paint'],
                ['3','3.950', '2', 'paint'],
                ['3','4.647', '3', 'paint'],
                ['3','5.236', '4', 'paint'],
                ['4','2.612', '1', 'paint'],
                ['4','3.526', '2', 'paint'],
                ['4','4.112', '3', 'paint'],
                ['4','4.593', '4', 'paint'],
                ['5','2.353', '1', 'paint'],
                ['5','3.149', '2', 'paint'],
                ['5','3.639', '3', 'paint'],
                ['5','4.029', '4', 'paint'],
                ['6','2.120', '1', 'paint'],
                ['6','2.811', '2', 'paint'],
                ['6','3.221', '3', 'paint'],
                ['6','3.534', '4', 'paint'],
                ['7','1.910', '1', 'paint'],
                ['7','2.510', '2', 'paint'],
                ['7','2.850', '3', 'paint'],
                ['7','3.100', '4', 'paint'],
                ['1','9.184', '1', 'handsewn_stripes'],
                ['1','16.146', '2', 'handsewn_stripes'],
                ['1','23.401', '3', 'handsewn_stripes'],
                ['1','31.366', '4', 'handsewn_stripes'],
                ['2','8.274', '1', 'handsewn_stripes'],
                ['2','14.416', '2', 'handsewn_stripes'],
                ['2','20.709', '3', 'handsewn_stripes'],
                ['2','27.514', '4', 'handsewn_stripes'],
                ['3','7.454', '1', 'handsewn_stripes'],
                ['3','12.871', '2', 'handsewn_stripes'],
                ['3','18.327', '3', 'handsewn_stripes'],
                ['3','24.135', '4', 'handsewn_stripes'],
                ['4','6.715', '1', 'handsewn_stripes'],
                ['4','11.492', '2', 'handsewn_stripes'],
                ['4','16.218', '3', 'handsewn_stripes'],
                ['4','21.171', '4', 'handsewn_stripes'],
                ['5','6.050', '1', 'handsewn_stripes'],
                ['5','10.261', '2', 'handsewn_stripes'],
                ['5','14.352', '3', 'handsewn_stripes'],
                ['5','18.571', '4', 'handsewn_stripes'],
                ['6','5.450', '1', 'handsewn_stripes'],
                ['6','9.162', '2', 'handsewn_stripes'],
                ['6','12.701', '3', 'handsewn_stripes'],
                ['6','16.291', '4', 'handsewn_stripes'],
                ['7','4.910', '1', 'handsewn_stripes'],
                ['7','8.180', '2', 'handsewn_stripes'],
                ['7','11.240', '3', 'handsewn_stripes'],
                ['7','14.290', '4', 'handsewn_stripes'],
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
            \SwiftOtter\ProductDecorator\Setup\Patch\Data\CreateTiers::class,
            \SwiftOtter\ProductDecorator\Setup\Patch\Data\CreatePrintMethods::class
        ];
    }
}
