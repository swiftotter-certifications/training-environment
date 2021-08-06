<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface PriceRequestInterface
{
    /**
     * @return string
     */
    public function getClientId(): ?string;

    /**
     * @param string|null $clientId
     * @return void
     */
    public function setClientId(?string $clientId): void;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface[] $products
     * @return void
     */
    public function setProducts(array $products): void;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface[]
     */
    public function getProducts(): array;

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface[] $locations
     * @return void
     */
    public function setLocations(array $locations): void;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface[]
     */
    public function getLocations(): array;

    /**
     * @return int
     */
    public function getTotalQuantity(): int;

    /**
     * @return \SwiftOtter\ProductDecorator\Api\Data\PriceRequestExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \SwiftOtter\ProductDecorator\Api\Data\PriceRequestExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\SwiftOtter\ProductDecorator\Api\Data\PriceRequestExtensionInterface $extensionAttributes);
}
