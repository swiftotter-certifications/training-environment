<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PriceResponse;

interface InternalProductResponseInterface extends ProductResponseInterface
{
    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface[]
     */
    public function getAmounts(): array;
}
