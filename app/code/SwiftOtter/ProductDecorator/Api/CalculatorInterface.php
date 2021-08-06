<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\Calculator\ProductResponseInterface as CalculatorProductInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;

interface CalculatorInterface
{
    /**
     * @param PriceRequestInterface $request
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface $response
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface
     */
    public function calculate(
        PriceRequestInterface $request,
        ProductResponseInterface $response
    ): ProductResponseInterface;
}
