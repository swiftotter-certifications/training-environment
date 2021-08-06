<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PriceResponse;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\TierInterface;

interface ProductResponseInterface
{
    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface
     */
    public function getProduct(): ProductRequestInterface;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\TierInterface
     */
    public function getTier(): TierInterface;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface $amount
     * @return void
     */
    public function addAmount(AmountResponseInterface $amount): void;

    /**
     * @return float
     */
    public function getTotal(): float;
}
