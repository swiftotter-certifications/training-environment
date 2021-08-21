<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use SwiftOtter\ProductDecorator\Api\Data\DetailedPriceResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\TierInterface;

class PriceResponseProduct implements ProductResponseInterface, DetailedPriceResponseInterface
{
    private $amounts = [];

    /** @var ProductRequestInterface */
    private $product;

    /** @var TierInterface */
    private $tier;

    public function __construct(ProductRequestInterface $product, TierInterface $tier)
    {
        $this->product = $product;
        $this->tier = $tier;
    }

    public function getProduct(): ProductRequestInterface
    {
        return $this->product;
    }

    public function getTier(): TierInterface
    {
        return $this->tier;
    }

    public function addAmount(AmountResponseInterface $amount): void
    {
        $this->amounts[] = $amount;
    }

    public function getAmounts(): array
    {
        return $this->amounts;
    }

    public function getTotal(): float
    {
        return array_reduce($this->amounts, function(float $total, AmountResponseInterface $amount) {
            return $total + ($amount->getAmount() * $this->getProduct()->getQuantity());
        }, 0);
    }
}
