<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface as IncomingShareRequest;

interface AddToCartDetailsInterface extends ExtensibleDataInterface
{
    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void;

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty);

    /**
     * @return \SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface[]
     */
    public function getChildren(): array;

    /**
     * @param \SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface[] $children
     */
    public function setChildren(array $children): void;

    /**
     * @return \SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface|null
     */
    public function getShare(): ?IncomingShareRequest;

    /**
     * @param \SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface $request
     */
    public function setShare(IncomingShareRequest $request): void;

    /**
     * @return \SwiftOtter\Checkout\Api\Data\AddToCartDetailsExtensionInterface
     */
    public function getExtensionAttributes(): \SwiftOtter\Checkout\Api\Data\AddToCartDetailsExtensionInterface;

    /**
     * @param \SwiftOtter\Checkout\Api\Data\AddToCartDetailsExtensionInterface $extensionAttributes
     * @return void
     */
    public function setExtensionAttributes(array $extensionAttributes): void;
}
