<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/9/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterfaceFactory as PriceRequestFactory;

class HydratePriceRequestFromJson
{
    /** @var PriceRequestFactory */
    private $priceRequestFactory;

    /** @var LocationRequestInterfaceFactory */
    private $locationRequestFactory;

    /** @var ProductRequestInterfaceFactory */
    private $productRequestFactory;

    public function __construct(
        PriceRequestFactory $priceRequestFactory,
        LocationRequestInterfaceFactory $locationRequestInterfaceFactory,
        ProductRequestInterfaceFactory $productRequestInterfaceFactory
    ) {
        $this->priceRequestFactory = $priceRequestFactory;
        $this->locationRequestFactory = $locationRequestInterfaceFactory;
        $this->productRequestFactory = $productRequestInterfaceFactory;
    }

    public function execute(array $details): PriceRequestInterface
    {
        $priceRequest = $this->priceRequestFactory->create();

        $productRequests = [];
        foreach ($details['products'] ?? [] as $product) {
            $productRequest = $this->productRequestFactory->create();
            $productRequest->setSku($product['sku']);
            $productRequest->setQuantity((int)$product['quantity']);

            $productRequests[] = $productRequest;
        }

        $priceRequest->setProducts($productRequests);

        $locationRequests = [];
        foreach ($details['locations'] ?? [] as $location) {
            $locationRequest = $this->locationRequestFactory->create();
            $locationRequest->setLocationId((int)$location['location_id']);
            $locationRequest->setPrintMethodId($location['print_method_id']);
            $locationRequest->setDisplayText($location['display_text']);
            $locationRequest->setColors($location['colors']);

            $locationRequests[] = $locationRequest;
        }
        $priceRequest->setLocations($locationRequests);

        return $priceRequest;
    }
}
