<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Calculator;

use SwiftOtter\ProductDecorator\Api\CalculatorInterface;
use SwiftOtter\ProductDecorator\Api\Data\Calculator\ProductResponseInterface as CalculatorProductInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;

class CompositeCalculator implements CalculatorInterface
{
    /** @var array */
    private $calculators;

    public function __construct(array $calculators)
    {
        $this->calculators = $calculators;
    }

    public function calculate(PriceRequestInterface $request, ProductResponseInterface $response): ProductResponseInterface
    {
        /** @var CalculatorInterface $calculator */
        foreach ($this->calculators as $calculator) {
            $response = $calculator->calculate($request, $response);
        }

        return $response;
    }
}
