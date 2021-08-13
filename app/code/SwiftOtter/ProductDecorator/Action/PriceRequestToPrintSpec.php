<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface as LocationRequest;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpec\LocationInterfaceFactory as LocationFactory;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceFactory as PrintSpecFactory;
use SwiftOtter\ProductDecorator\Api\PrintSpecRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location as PrintSpecLocationResource;
use SwiftOtter\Utils\Action\ClassCopier;

class PriceRequestToPrintSpec
{
    /** @var LocationFactory */
    private $locationFactory;

    /** @var PrintSpecFactory */
    private $printSpecFactory;

    /** @var PrintSpecRepositoryInterface */
    private $printSpecRepository;

    /** @var PrintSpecLocationResource */
    private $printSpecLocationResource;

    /** @var ClassCopier */
    private $classCopier;

    /** @var PrintSpecResource */
    private $printSpecResource;

    public function __construct(
        PrintSpecRepositoryInterface $printSpecRepository,
        PrintSpecLocationResource $printSpecLocationResource,
        PrintSpecFactory $printSpecFactory,
        LocationFactory $locationFactory,
        PrintSpecResource $printSpecResource,
        ClassCopier $classCopier
    ) {
        $this->locationFactory = $locationFactory;
        $this->printSpecFactory = $printSpecFactory;
        $this->printSpecRepository = $printSpecRepository;
        $this->printSpecLocationResource = $printSpecLocationResource;
        $this->classCopier = $classCopier;
        $this->printSpecResource = $printSpecResource;
    }

    public function execute(PriceRequestInterface $priceRequest): PrintSpecInterface
    {
        $printSpec = $this->printSpecFactory->create();
        $printSpec->setName("Loaded at " . time());
        if ($priceRequest->getClientId()) {
            $id = $this->printSpecResource->getIdByClientId($priceRequest->getClientId());
            $printSpec = $this->printSpecRepository->getById($id);
        }

        $this->printSpecRepository->save($printSpec);

        $printSpecLocations = [];
        foreach ($priceRequest->getLocations() as $location) {
            $printSpecLocation = $this->locationFactory->create();
            $this->classCopier->execute($location, $printSpecLocation, LocationRequest::class);
            $printSpecLocation->setPrintSpecId($printSpec->getId());

            $this->printSpecLocationResource->save($printSpecLocation);
            $printSpecLocations[] = $printSpecLocation;
        }

        $printSpec->getExtensionAttributes()->setLocations($printSpecLocations);

        return $printSpec;
    }
}
