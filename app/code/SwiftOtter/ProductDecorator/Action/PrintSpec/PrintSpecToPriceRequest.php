<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action\PrintSpec;

use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface as LocationRequest;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterfaceFactory as LocationRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterfaceFactory as ProductRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterfaceFactory as PriceRequestFactory;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface as PrintSpec;
use SwiftOtter\ProductDecorator\Api\PrintSpecRepositoryInterface as PrintSpecRepository;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location\CollectionFactory as LocationCollectionFactory;
use SwiftOtter\Utils\Action\ClassCopier;
use Swiftotter\Utils\Model\UnifiedSale\Item;
use Swiftotter\Utils\Model\UnifiedSale\Item as UnifiedSaleItem;

class PrintSpecToPriceRequest
{
    /** @var PriceRequestFactory */
    private $priceRequestFactory;

    /** @var ProductRequestFactory */
    private $productRequestFactory;

    /** @var LocationRequestFactory */
    private $locationRequestFactory;

    /** @var PrintSpecRepository */
    private $printSpecRepository;

    /** @var Location\CollectionFactory */
    private $locationCollectionFactory;

    /** @var ClassCopier */
    private $classCopier;

    public function __construct(
        PriceRequestFactory       $priceRequestFactory,
        ProductRequestFactory     $productRequestFactory,
        LocationRequestFactory    $locationRequestFactory,
        PrintSpecRepository       $printSpecRepository,
        LocationCollectionFactory $locationCollectionFactory,
        ClassCopier               $classCopier
    ) {
        $this->priceRequestFactory = $priceRequestFactory;
        $this->productRequestFactory = $productRequestFactory;
        $this->locationRequestFactory = $locationRequestFactory;
        $this->printSpecRepository = $printSpecRepository;
        $this->locationCollectionFactory = $locationCollectionFactory;
        $this->classCopier = $classCopier;
    }

    /**
     * @param int $printSpecId
     * @param array<int, UnifiedSaleItem> $saleItems
     * @return PriceRequestInterface
     */
    public function execute(int $printSpecId, array $saleItems): PriceRequestInterface
    {
        $printSpec = $this->printSpecRepository->getById($printSpecId);

        $request = $this->priceRequestFactory->create();
        $request->setClientId($printSpec->getClientId());

        $request->setLocations($this->getLocations($printSpec));
        $request->setProducts($this->getProducts($saleItems));

        return $request;
    }

    private function getLocations(PrintSpec $printSpec): array
    {
        $locations = $this->locationCollectionFactory->create()
            ->addFieldToFilter('print_spec_id', $printSpec->getId());
        $output = [];

        foreach ($locations as $location) {
            $locationRequest = $this->locationRequestFactory->create();
            $this->classCopier->execute($location, $locationRequest, LocationRequest::class);

            $output[] = $locationRequest;
        }

        return $output;
    }

    /**
     * @param array<int, UnifiedSaleItem> $saleItems
     * @return array<int, QuoteItem>
     */
    private function getProducts(array $saleItems): array
    {
        $output = [];

        foreach ($saleItems as $saleItem) {
            if ($saleItem->getProductType() !== ProductType::TYPE_SIMPLE) {
                continue;
            }

            $productRequest = $this->productRequestFactory->create();
            $productRequest->setSku($saleItem->getSku());
            $productRequest->setQuantity((int)$saleItem->getTotalQty());

            $output[] = $productRequest;
        }

        return $output;
    }
}
