<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\DefaultPricingForDecorated;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\SpecialPrice as SpecialPriceModel;
use Magento\Framework\Pricing\PriceCurrencyInterface as PriceCurrency;
use SwiftOtter\ProductDecorator\Action\AddDefaultDecorationChargeToPrice;
use SwiftOtter\ProductDecorator\Attributes;

class SpecialPrice
{
    /** @var AddDefaultDecorationChargeToPrice */
    private $addDefaultDecorationChargeToPrice;

    public function __construct(AddDefaultDecorationChargeToPrice $addDefaultDecorationChargeToPrice)
    {
        $this->addDefaultDecorationChargeToPrice = $addDefaultDecorationChargeToPrice;
    }

    public function afterGetValue(SpecialPriceModel $regularPriceModel, $result)
    {
        if (!($regularPriceModel->getProduct() instanceof ProductInterface)
            || $result === false) {
            return $result;
        }

        return $this->addDefaultDecorationChargeToPrice->execute($result, $regularPriceModel->getProduct());
    }
}
