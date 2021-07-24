<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/26/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Api\Data;

interface PriceResponseInterface
{
    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return float
     */
    public function getBasePrice(): float;

    /**
     * @return float|null
     */
    public function getTaxAmount(): ?float;

    /**
     * @return float|null
     */
    public function getBaseTaxAmount(): ?float;

    /**
     * @return string
     */
    public function getFormattedPrice(): string;

    /**
     * @return string
     */
    public function getFormattedPriceWithTax(): string;

    /**
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @return bool
     */
    public function getTaxShouldBeApplied(): bool;

    /**
     * @return bool
     */
    public function getCouponIsApplied(): bool;
}
