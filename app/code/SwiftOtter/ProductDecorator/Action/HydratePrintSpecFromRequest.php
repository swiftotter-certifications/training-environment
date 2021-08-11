<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterfaceFactory as PriceRequestFactory;

class HydratePrintSpecFromRequest
{
    public function __construct(PriceRequestFactory $priceRequestFactory)
    {}

    public function execute(array $details): PriceRequestInterface
    {

    }
}
