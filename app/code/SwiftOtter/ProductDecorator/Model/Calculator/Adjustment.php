<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Calculator;

use SwiftOtter\ProductDecorator\Api\CalculatorInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;

class Adjustment implements CalculatorInterface
{
    public function calculate(PriceRequestInterface $request, ProductResponseInterface $response): ProductResponseInterface
    {
        /**
         * This does nothing for now, but will allow for price overrides.
         */
        return $response;
    }

}
