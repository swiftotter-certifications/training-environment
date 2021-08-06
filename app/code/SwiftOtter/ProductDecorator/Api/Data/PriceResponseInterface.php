<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

use SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface;

interface PriceResponseInterface
{
    /**
     * @return float|null
     */
    public function getBasePrice(): ?float;

    /**
     * @return bool
     */
    public function getSuccess(): bool;

    /**
     * @return array
     */
    public function getProducts(): array;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface $product
     */
    public function addProduct(ProductResponseInterface $product): void;
}
