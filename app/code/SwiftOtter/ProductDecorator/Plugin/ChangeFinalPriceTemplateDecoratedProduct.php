<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Pricing\Render\AmountRenderInterface as AmountRender;
use Magento\Framework\Pricing\Render\PriceBoxRenderInterface as PriceBoxRender;
use Magento\Framework\Pricing\Render\RendererPool as Subject;
use Magento\Framework\Pricing\SaleableInterface;
use SwiftOtter\ProductDecorator\Attributes;

class ChangeFinalPriceTemplateDecoratedProduct
{
    public function afterCreateAmountRender(
        Subject $subject,
        AmountRender $output
    ) {
        if (!($output->getSaleableItem() instanceof ProductInterface)
            || $output->getData('price_type_code') !== 'final_price'
            || $output->getTemplate() !== 'Magento_Catalog::product/price/amount/default.phtml') {
            return $output;
        }

        if (!$output->getSaleableItem()->getData(Attributes::ENABLED)) {
            return $output;
        }

        $output->setTemplate('SwiftOtter_ProductDecorator::product/price/amount.phtml');
        return $output;
    }
}
