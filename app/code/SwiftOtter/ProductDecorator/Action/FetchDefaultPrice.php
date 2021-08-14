<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterfaceFactory as ProductRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterfaceFactory as LocationRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterfaceFactory as PriceRequestFactory;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequestLocation;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResource;
use SwiftOtter\ProductDecorator\Service\Tier as TierService;
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;

class FetchDefaultPrice
{
    /** @var FastProductRepositoryInterface */
    private $fastProductRepository;

    /** @var CalculatePrice */
    private $calculatePrice;

    /** @var PriceRequestFactory */
    private $priceRequestFactory;

    /** @var ProductRequestFactory */
    private $productRequestFactory;

    /** @var LocationRequestFactory */
    private $locationRequestFactory;

    /** @var TierService */
    private $tierService;

    /** @var LocationPrintMethodResource */
    private $locationPrintMethodResource;

    public function __construct(
        FastProductRepositoryInterface $fastProductRepository,
        CalculatePrice $calculatePrice,
        PriceRequestFactory $priceRequestFactory,
        ProductRequestFactory $productRequestFactory,
        LocationRequestFactory $locationRequestFactory,
        TierService $tierService,
        LocationPrintMethodResource $locationPrintMethodResource
    ) {
        $this->fastProductRepository = $fastProductRepository;
        $this->calculatePrice = $calculatePrice;
        $this->priceRequestFactory = $priceRequestFactory;
        $this->productRequestFactory = $productRequestFactory;
        $this->locationRequestFactory = $locationRequestFactory;
        $this->tierService = $tierService;
        $this->locationPrintMethodResource = $locationPrintMethodResource;
    }

    public function execute($sku): float
    {
        $product = $this->productRequestFactory->create();
        $product->setSku($sku);
        $product->setQuantity($this->tierService->getMinimumTier($sku)->getMinTier());

        $location = $this->locationRequestFactory->create();
        $location->setColors([PriceRequestLocation::DEFAULT_COLOR]);

        $locationIds = $this->locationPrintMethodResource->getPreferredLocationsIdFor($sku);
        $option = $this->locationPrintMethodResource->getBestPrintMethodFor($sku, $locationIds);
        $location->setPrintMethodId($option['print_method_id']);
        $location->setLocationId($option['location_id']);

        $priceRequest = $this->priceRequestFactory->create();
        $priceRequest->setExcludeProductPrice(true);
        $priceRequest->setProducts([$product]);
        $priceRequest->setLocations([$location]);

        $response = $this->calculatePrice->execute($priceRequest);
        return $response->getBasePrice();
    }
}
