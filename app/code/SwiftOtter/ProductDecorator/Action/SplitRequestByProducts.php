<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterfaceFactory as LocationRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterfaceFactory as ProductRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterfaceFactory as PriceRequestFactory;

class SplitRequestByProducts
{
    /** @var PriceRequestFactory */
    private $priceRequestFactory;

    /** @var ProductRequestFactory */
    private $productRequestFactory;

    /** @var LocationRequestFactory */
    private $locationRequestFactory;

    public function __construct(
        PriceRequestFactory $priceRequestFactory,
        ProductRequestFactory $productRequestFactory,
        LocationRequestFactory $locationRequestFactory
    ) {
        $this->priceRequestFactory = $priceRequestFactory;
        $this->productRequestFactory = $productRequestFactory;
        $this->locationRequestFactory = $locationRequestFactory;
    }

    /**
     * @param PriceRequestInterface $priceRequest
     * @return PriceRequestInterface[]
     */
    public function execute(PriceRequestInterface $priceRequest): array
    {
        $output = [];
        foreach ($priceRequest->getProducts() as $product) {
            $output[] = $this->build($product, $priceRequest);
        }

        return $output;
    }

    private function build(ProductRequestInterface $productRequest, PriceRequestInterface $oldPriceRequest): PriceRequestInterface
    {
        $priceRequest = $this->priceRequestFactory->create();

        $newLocations = [];
        foreach ($oldPriceRequest->getLocations() as $location) {
            $newLocation = clone $location;
            $newLocation[] = $newLocation;
        }

        $priceRequest->setLocations($newLocations);

        $product = clone $productRequest;
        $priceRequest->setProducts([$product]);

        return $priceRequest;
    }
}
