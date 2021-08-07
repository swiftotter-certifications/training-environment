<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use Magento\Framework\Pricing\Helper\Data as CurrencyHelper;
use SwiftOtter\ProductDecorator\Api\Data\Calculator\ProductResponseInterface as CalculatorProductInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceResponseInterface;

class PriceResponse implements PriceResponseInterface
{
    private $products = [];

    /** @var bool */
    private $success;
    /** @var CurrencyHelper */
    private $currencyHelper;

    public function __construct(bool $success, CurrencyHelper $currencyHelper)
    {
        $this->success = $success;
        $this->currencyHelper = $currencyHelper;
    }

    public function getBasePrice(): ?float
    {
        return array_reduce($this->products, function(float $total, ProductResponseInterface $product) {
            return $total + ($product->getTotal() * $product->getProduct()->getQuantity());
        }, 0);
    }

    public function getFormattedBasePrice(): ?string
    {
        return $this->currencyHelper->currency($this->getBasePrice(), true, false);
    }

    public function getUnitPrice(): ?float
    {
        $quantity = $this->getTotalQuantity();
        if (!$quantity) {
            return 0;
        }

        return $this->getBasePrice() / $quantity;
    }

    public function getFormattedUnitPrice(): ?string
    {
        return $this->currencyHelper->currency($this->getUnitPrice(), true, false);
    }

    private function getTotalQuantity(): int
    {
        return array_reduce($this->products, function(float $total, ProductResponseInterface $product) {
            return $product->getProduct()->getQuantity();
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
