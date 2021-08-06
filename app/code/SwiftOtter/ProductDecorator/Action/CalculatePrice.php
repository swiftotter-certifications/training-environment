<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterfaceFactory as ProductResponseFactory;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterfaceFactory as PriceResponseFactory;
use SwiftOtter\ProductDecorator\Model\CompositeCalculator;
use SwiftOtter\ProductDecorator\Service\Tier as TierService;


class CalculatePrice
{
    /** @var PriceResponseFactory */
    private $priceResponseFactory;

    /** @var TierService */
    private $tierService;

    /** @var ProductResponseFactory */
    private $productResponseFactory;

    /** @var CompositeCalculator */
    private $compositeCalculator;

    public function __construct(
        PriceResponseFactory $priceResponseFactory,
        TierService $tier,
        ProductResponseFactory $productResponseFactory,
        CompositeCalculator $compositeCalculator
    ) {
        $this->priceResponseFactory = $priceResponseFactory;
        $this->tierService = $tier;
        $this->productResponseFactory = $productResponseFactory;
        $this->compositeCalculator = $compositeCalculator;
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

            $response->addProduct(
                $this->compositeCalculator->calculate($priceRequest, $responseProduct)
            );

            $response = null;
        }
    }
}
