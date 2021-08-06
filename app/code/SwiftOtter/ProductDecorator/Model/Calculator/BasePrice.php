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
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;

class BasePrice implements CalculatorInterface
{
    /** @var FastProductRepositoryInterface */
    private $productRepository;

    /** @var AmountResponseFactory */
    private $amountResponseFactory;

    public function __construct(FastProductRepositoryInterface $productRepository, AmountResponseFactory $amountResponseFactory)
    {
        $this->productRepository = $productRepository;
        $this->amountResponseFactory = $amountResponseFactory;
    }

    public function calculate(PriceRequestInterface $request, ProductResponseInterface $response): ProductResponseInterface
    {
        $product = $this->productRepository->get($response->getProduct()->getSku(), null, null, null, ['price', 'tier_price']);
        $amount = $this->amountResponseFactory->create([
            'amount' => $product->getFinalPrice($response->getProduct()->getQuantity()) / $response->getProduct()->getQuantity(),
            'calculator' => $this
        ]);

        $response->addAmount($amount);
        return $response;
    }
}
