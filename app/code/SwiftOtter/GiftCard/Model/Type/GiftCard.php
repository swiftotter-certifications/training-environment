<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/23/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Type;

use Laminas\Config\Processor\Constant;
use SwiftOtter\GiftCard\Constants;

class GiftCard extends \Magento\Catalog\Model\Product\Type\Virtual
{
    const TYPE_CODE = 'giftcard';

    public function isSalable($product)
    {
        $product->setData('is_salable', true);
        return parent::isSalable($product);
    }

    public function prepareForCartAdvanced(\Magento\Framework\DataObject $buyRequest, $product, $processMode = null)
    {
        $products = parent::prepareForCartAdvanced($buyRequest, $product, $processMode);

        foreach ($products as $product) {
            foreach (Constants::OPTION_LIST as $option) {
                $product->addCustomOption(
                    $option,
                    $buyRequest->getData($option)
                );
            }
        }

        return $products;
    }
}