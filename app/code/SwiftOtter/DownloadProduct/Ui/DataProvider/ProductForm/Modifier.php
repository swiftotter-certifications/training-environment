<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/9/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Ui\DataProvider\ProductForm;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class Modifier extends AbstractModifier
{

    /**
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        unset($meta['advanced_inventory_modal']);
        return $meta;
    }
}
