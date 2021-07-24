<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2018/04/18
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Plugin\Catalog\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use SwiftOtter\CategoryAsProduct\Model\Product\Type;

class ProductUrlUpdate
{
    public function afterGetProductUrl(ProductInterface $product, $value)
    {
        if ($product->getTypeId() !== Type::TYPE_ID) {
            return $value;
        } else {
            return $product->getTypeInstance()->getUrl($product);
        }
    }
}