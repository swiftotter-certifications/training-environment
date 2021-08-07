<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Product\View as ProductViewHelper;
use Magento\Framework\View\Result\Page as ResultPage;
use SwiftOtter\ProductDecorator\Attributes;

class AddDecoratorHandleToProductPage
{
    const PRODUCT_LAYOUT_HANDLE = 'catalog_product_view_decorated';

    public function beforeInitProductLayout(
        ProductViewHelper $subject,
        $resultPage,
        ProductInterface $product
    ) {
        if (!($resultPage instanceof ResultPage)) {
            return null;
        }

        if ($product->getData(Attributes::ENABLED)
            || ($product->getCustomAttribute(Attributes::ENABLED)
                && $product->getCustomAttribute(Attributes::ENABLED)->getValue())) {
            $resultPage->addHandle([static::PRODUCT_LAYOUT_HANDLE]);
        }

        return null;
    }
}
