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
                ['10', '69.411', '1', 'embroidery'],
                ['10', '81.203', '2', 'embroidery'],
                ['10', '96.103', '3', 'embroidery'],
                ['10', '112.558', '4', 'embroidery'],
                ['4', '62.533', '1', 'embroidery'],
                ['4', '72.503', '2', 'embroidery'],
                ['4', '85.047', '3', 'embroidery'],
                ['4', '98.735', '4', 'embroidery'],
                ['13', '56.336', '1', 'embroidery'],
                ['13', '64.735', '2', 'embroidery'],
                ['13', '75.263', '3', 'embroidery'],
                ['13', '86.61', '4', 'embroidery'],
                ['11', '50.753', '1', 'embroidery'],
                ['11', '57.799', '2', 'embroidery'],
                ['11', '66.604', '3', 'embroidery'],
                ['11', '75.974', '4', 'embroidery'],
                ['7', '45.723', '1', 'embroidery'],
                ['7', '51.606', '2', 'embroidery'],
                ['7', '58.942', '3', 'embroidery'],
                ['7', '66.643', '4', 'embroidery'],
                ['8', '41.192', '1', 'embroidery'],
                ['8', '46.077', '2', 'embroidery'],
                ['8', '52.161', '3', 'embroidery'],
                ['8', '58.459', '4', 'embroidery'],
                ['9', '37.11', '1', 'embroidery'],
                ['9', '41.14', '2', 'embroidery'],
                ['9', '46.16', '3', 'embroidery'],
                ['9', '51.28', '4', 'embroidery'],
                ['10', '35.706', '1', 'paint'],
                ['10', '43.207', '2', 'paint'],
                ['10', '55.984', '3', 'paint'],
                ['10', '68.264', '4', 'paint'],
                ['4', '32.168', '1', 'paint'],
                ['4', '38.578', '2', 'paint'],
                ['4', '49.543', '3', 'paint'],
                ['4', '59.88', '4', 'paint'],
                ['13', '28.98', '1', 'paint'],
                ['13', '34.444', '2', 'paint'],
                ['13', '43.843', '3', 'paint'],
                ['13', '52.527', '4', 'paint'],
                ['11', '26.108', '1', 'paint'],
                ['11', '30.754', '2', 'paint'],
                ['11', '38.8', '3', 'paint'],
                ['11', '46.076', '4', 'paint'],
                ['7', '23.521', '1', 'paint'],
                ['7', '27.459', '2', 'paint'],
                ['7', '34.336', '3', 'paint'],
                ['7', '40.418', '4', 'paint'],
                ['8', '21.19', '1', 'paint'],
                ['8', '24.517', '2', 'paint'],
                ['8', '30.386', '3', 'paint'],
                ['8', '35.454', '4', 'paint'],
                ['9', '19.09', '1', 'paint'],
                ['9', '21.89', '2', 'paint'],
                ['9', '26.89', '3', 'paint'],
                ['9', '31.1', '4', 'paint'],
                ['10', '171.91', '1', 'handsewn_stripes'],
                ['10', '189.842', '2', 'handsewn_stripes'],
                ['10', '217.023', '3', 'handsewn_stripes'],
                ['10', '253.058', '4', 'handsewn_stripes'],
                ['4', '154.874', '1', 'handsewn_stripes'],
                ['4', '169.502', '2', 'handsewn_stripes'],
                ['4', '192.055', '3', 'handsewn_stripes'],
                ['4', '221.981', '4', 'handsewn_stripes'],
                ['13', '139.526', '1', 'handsewn_stripes'],
                ['13', '151.341', '2', 'handsewn_stripes'],
                ['13', '169.961', '3', 'handsewn_stripes'],
                ['13', '194.72', '4', 'handsewn_stripes'],
                ['11', '125.699', '1', 'handsewn_stripes'],
                ['11', '135.126', '2', 'handsewn_stripes'],
                ['11', '150.408', '3', 'handsewn_stripes'],
                ['11', '170.807', '4', 'handsewn_stripes'],
                ['7', '113.242', '1', 'handsewn_stripes'],
                ['7', '120.648', '2', 'handsewn_stripes'],
                ['7', '133.104', '3', 'handsewn_stripes'],
                ['7', '149.831', '4', 'handsewn_stripes'],
                ['8', '102.02', '1', 'handsewn_stripes'],
                ['8', '107.722', '2', 'handsewn_stripes'],
                ['8', '117.791', '3', 'handsewn_stripes'],
                ['8', '131.431', '4', 'handsewn_stripes'],
                ['9', '91.91', '1', 'handsewn_stripes'],
                ['9', '96.18', '2', 'handsewn_stripes'],
                ['9', '104.24', '3', 'handsewn_stripes'],
                ['9', '115.29', '4', 'handsewn_stripes'],
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
