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
     * @return string|null
     */
    public function getFormattedBasePrice(): ?string;

    /**
     * @return float|null
     */
    public function getUnitPrice(): ?float;

    /**
     * @return string|null
     */
    public function getFormattedUnitPrice(): ?string;

    /**
     * @return bool
     */
    public function getSuccess(): bool;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface[]
     */
    public function getProducts(): array;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceResponse\ProductResponseInterface $product
     * @return void
     */
    public function addProduct(ProductResponseInterface $product): void;
}
