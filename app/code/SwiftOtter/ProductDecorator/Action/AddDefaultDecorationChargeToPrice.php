<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface as PriceCurrency;
use SwiftOtter\ProductDecorator\Attributes;

class AddDefaultDecorationChargeToPrice
{
    /** @var PriceCurrency */
    private $priceCurrency;

    public function __construct(PriceCurrency $priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    public function execute(?float $amount, ProductInterface $product)
    {
        if (!($product instanceof Product)
            || !$product->getData(Attributes::DEFAULT_DECORATION_CHARGE)
            || !$amount) {
            return $amount;
        }

        $decorationAmount = $product->getData(Attributes::DEFAULT_DECORATION_CHARGE);
        if (!$decorationAmount) {
            return $amount;
        }

        $decorationAmount = $this->priceCurrency->convertAndRound($decorationAmount);
        $amount += $decorationAmount;

        return $amount;
    }
}
