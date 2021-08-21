<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/16/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface;

interface DetailedPriceResponseInterface
{
    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface[]
     */
    public function getAmounts(): array;
}
