<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PriceResponse;

use SwiftOtter\ProductDecorator\Api\CalculatorInterface;

interface AmountResponseInterface
{
    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\CalculatorInterface
     */
    public function getCalculator(): CalculatorInterface;

    /**
     * @return string|null
     */
    public function getNotes(): ?string;
}
