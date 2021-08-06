<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PriceRequest;

interface ProductRequestInterface
{
    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param int $quantity
     * @return void
     */
    public function setQuantity(int $quantity): void;

    /**
     * @return int
     */
    public function getQuantity(): int;
}
