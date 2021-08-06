<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface PrintChargeInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return int
     */
    public function getTierId(): int;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return int
     */
    public function getColors(): int;

    /**
     * @return string
     */
    public function getPriceType(): string;

    /**
     * @return int
     */
    public function getMinLookup(): ?int;

    /**
     * @return int
     */
    public function getMaxLookup(): ?int;

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void;

    /**
     * @param int $tierId
     * @return void
     */
    public function setTierId(int $tierId): void;

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void;

    /**
     * @param int $colors
     * @return void
     */
    public function setColors(int $colors): void;

    /**
     * @param string $priceType
     * @return void
     */
    public function setPriceType(string $priceType): void;

    /**
     * @param int|null $value
     * @return void
     */
    public function setMinLookup(?int $value): void;

    /**
     * @param int|null $value
     * @return void
     */
    public function setMaxLookup(?int $value): void;
}
