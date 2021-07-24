<?php
/**
 * @by SwiftOtter, Inc., 05/22/2017
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Model\Product;

class Price extends \Magento\Catalog\Model\Product\Type\Price
{
    /**
     * {@inheritdoc}
     */
    public function getPrice($product)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getFinalPrice($qty, $product)
    {
        return 0;
    }
}