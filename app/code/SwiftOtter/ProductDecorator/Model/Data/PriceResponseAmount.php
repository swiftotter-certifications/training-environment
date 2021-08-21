<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use SwiftOtter\ProductDecorator\Api\CalculatorInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface;

class PriceResponseAmount implements AmountResponseInterface
{
    /** @var float */
    private $amount;

    /** @var CalculatorInterface */
    private $calculator;

    /** @var string|null */
    private $notes;

    public function __construct(float $amount, CalculatorInterface $calculator, ?string $notes = null)
    {
        $this->amount = $amount;
        $this->calculator = $calculator;
        $this->notes = $notes;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCalculator(): CalculatorInterface
    {
        return $this->calculator;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
