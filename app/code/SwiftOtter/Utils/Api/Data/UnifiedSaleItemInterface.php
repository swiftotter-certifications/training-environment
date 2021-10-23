<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Api\Data;

interface UnifiedSaleItemInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int|null
     */
    public function getProductId(): ?int;

    /**
     * @return int|null
     */
    public function getParentItemId(): ?int;

    /**
     * @return int
     */
    public function getTotalQty(): int;

    /**
     * @return string
     */
    public function getProductType(): string;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return \SwiftOtter\Utils\Api\Data\UnifiedSaleInterface|null
     */
    public function getParent(): ?UnifiedSaleInterface;

    /**
     * @return \SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface|null
     */
    public function getParentItem(): ?UnifiedSaleItemInterface;

    /**
     * @return \Magento\Quote\Api\Data\CartItemExtensionInterface|\Magento\Sales\Api\Data\OrderItemExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Magento\Quote\Api\Data\CartItemExtensionInterface|\Magento\Sales\Api\Data\OrderItemExtensionInterface
     * @return void
     */
    public function setExtensionAttributes($value): void;

    /**
     * @return \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Quote\Api\Data\CartItemInterface
     */
    public function get();

    /**
     * @return float|null
     */
    public function getCustomPrice(): ?float;

    /**
     * @return float|null
     */
    public function getOriginalCustomPrice(): ?float;

    /**
     * @param float|null $price
     */
    public function setCustomPrice(?float $price): void;

    /**
     * @param float|null $price
     */
    public function setOriginalCustomPrice(?float $price): void;
}
