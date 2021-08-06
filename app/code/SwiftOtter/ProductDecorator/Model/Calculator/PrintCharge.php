<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Calculator;

use SwiftOtter\ProductDecorator\Api\CalculatorInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterfaceFactory as AmountResponseFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge as PrintChargeResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;

class PrintCharge implements CalculatorInterface
{
    /** @var PrintMethodResource */
    private $printMethodResource;

    /** @var PrintChargeResource */
    private $printChargeResource;

    /** @var AmountResponseFactory */
    private $amountResponseFactory;

    public function __construct(
        PrintMethodResource $printMethodResource,
        PrintChargeResource $printChargeResource,
        AmountResponseFactory $amountResponseFactory
    ) {
        $this->printMethodResource = $printMethodResource;
        $this->printChargeResource = $printChargeResource;
        $this->amountResponseFactory = $amountResponseFactory;
    }

    public function calculate(PriceRequestInterface $request, ProductResponseInterface $response): ProductResponseInterface
    {
        foreach ($request->getLocations() as $location) {
            $priceType = $this->printMethodResource->getPriceType($location->getPrintMethodId());
            $charge = $this->printChargeResource->lookup($response->getTier()->getId(), count($location->getColors()), $priceType);

            $amountResponse = $this->amountResponseFactory->create(['amount' => $charge, 'calculator' => $this]);
            $response->addAmount($amountResponse);
        }

        return $response;
    }
}
