<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2019/03/26
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api;

interface PriceResponseInterface
{
    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @return string
     */
    public function getName(): string;

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
    public function getFormattedTaxAmount(): string;

    /**
     * @return float
     */
    public function getTaxAmount(): ?float;

    /**
     * @return float|null
     */
    public function getBaseTaxAmount(): ?float;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @return float
     */
    public function getBasePrice(): float;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return bool
     */
    public function getCouponIsApplied(): bool;

}
