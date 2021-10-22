<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/18/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote;

class AddPrintSpecDetailsToBuyRequest
{
    /**
     * @param Quote $subject
     * @param Product|mixed $product
     * @param float|\Magento\Framework\DataObject|null $request
     * @param string|null $processMode
     * @return array
     */
    public function beforeAddProduct(Quote $subject, Product $product, $request = null, AbstractType
}
