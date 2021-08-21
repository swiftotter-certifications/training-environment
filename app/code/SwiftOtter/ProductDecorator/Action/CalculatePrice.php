<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Psr\Log\LoggerInterface;
use SwiftOtter\ProductDecorator\Api\CalculatePriceInterface;
use SwiftOtter\ProductDecorator\Api\Data\DetailedPriceResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterfaceFactory as ProductResponseFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterfaceFactory as PriceResponseFactory;
use SwiftOtter\ProductDecorator\Model\Calculator\CompositeCalculator;
use SwiftOtter\ProductDecorator\Service\Tier as TierService;


class CalculatePrice implements CalculatePriceInterface
{
    /** @var PriceResponseFactory */
    private $priceResponseFactory;

    /** @var TierService */
    private $tierService;

    /** @var ProductResponseFactory */
    private $productResponseFactory;

    /** @var CompositeCalculator */
    private $compositeCalculator;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        PriceResponseFactory $priceResponseFactory,
        TierService $tier,
        ProductResponseFactory $productResponseFactory,
        CompositeCalculator $compositeCalculator,
        LoggerInterface $logger
    ) {
        $this->priceResponseFactory = $priceResponseFactory;
        $this->tierService = $tier;
        $this->productResponseFactory = $productResponseFactory;
        $this->compositeCalculator = $compositeCalculator;
        $this->logger = $logger;
    }

    public function execute(PriceRequestInterface $priceRequest): PriceResponseInterface
    {
        $response = $this->priceResponseFactory->create([
            'success' => true
        ]);

        foreach ($priceRequest->getProducts() as $product) {
            $tier = $this->tierService->getTier($product->getSku(), $product->getQuantity());

            $responseProduct = $this->productResponseFactory->create([
                'product' => $product,
                'tier' => $tier
            ]);

            $output = $this->compositeCalculator->calculate($priceRequest, $responseProduct);
            $this->writeLogs($priceRequest, $output);

            $response->addProduct($output);
        }

        return $response;
    }

    private function writeLogs(PriceRequestInterface $priceRequest, ProductResponseInterface $productResponse)
    {
        $details = [];

        $locations = [];
        foreach ($priceRequest->getLocations() as $location) {
            $locations[] = [
                'print_method_id' => $location->getPrintMethodId(),
                'colors' => $location->getColors(),
                'location_id' => $location->getLocationId(),
            ];
        }

        $details['locations'] = $locations;

        $details['product'] = [
            'sku' => $productResponse->getProduct()->getSku(),
            'quantity' => $productResponse->getProduct()->getQuantity(),
            'tier' => $productResponse->getTier()->getId(),
            'tier_min_quantity' => $productResponse->getTier()->getMinTier()
        ];

        if (!($productResponse instanceof DetailedPriceResponseInterface)) {
            $this->logger->warning('Product calculation information', $details);
            return;
        }

        $amounts = [];
        foreach ($productResponse->getAmounts() as $amount) {
            $amounts[] = [
                'amount' => $amount->getAmount(),
                'calculator' => get_class($amount->getCalculator()),
                'notes' => $amount->getNotes()
            ];
        }

        $details['amounts'] = $amounts;

        $this->logger->warning('Product calculation information', $details);
    }
}
