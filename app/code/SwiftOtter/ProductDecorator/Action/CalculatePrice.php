<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Psr\Log\LoggerInterface;
use SwiftOtter\ProductDecorator\Api\CalculatePriceInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\AmountResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\InternalProductResponseInterface as ProductResponse;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\InternalProductResponseInterfaceFactory as ProductResponseFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface as PriceResponse;
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

    public function execute(PriceRequestInterface $priceRequest): PriceResponse
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

            $calculated = $this->compositeCalculator->calculate($priceRequest, $responseProduct);
            $response->addProduct($calculated);

            $this->writeLogs($calculated);
        }

        return $response;
    }

    private function writeLogs(ProductResponse $productResponse): void
    {
        $details = [
            'tier_id' => $productResponse->getTier()->getId(),
            'tier_min' => $productResponse->getTier()->getMinTier(),
            'tier_max' => $productResponse->getTier()->getMaxTier(),
            'amounts' => []
        ];

        foreach ($productResponse->getAmounts() as $amount) {
            $details['amounts'][] = [
                'amount' => $amount->getAmount(),
                'calculator' => get_class($amount->getCalculator())
            ];
        }

        $this->logger->debug('Pricing output coming:', $details);
    }
}
