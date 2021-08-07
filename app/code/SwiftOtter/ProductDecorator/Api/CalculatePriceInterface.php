<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface;

interface CalculatePriceInterface
{
    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface $priceRequest
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface
     */
    public function execute(PriceRequestInterface $priceRequest): PriceResponseInterface;
}
