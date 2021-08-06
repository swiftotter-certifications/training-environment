<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use SwiftOtter\ProductDecorator\Api\Data\Calculator\ProductResponseInterface as CalculatorProductInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface;

class PriceResponse implements PriceResponseInterface
{
    private $products = [];

    /** @var bool */
    private $success;

    public function __construct(bool $success)
    {
        $this->success = $success;
    }

    public function getBasePrice(): ?float
    {
        return array_reduce($this->products, function(float $total, ProductResponseInterface $product) {
            return $total + $product->getTotal();
        }, 0);
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(ProductResponseInterface $product): void
    {
        $this->products[] = $product;
    }
}
