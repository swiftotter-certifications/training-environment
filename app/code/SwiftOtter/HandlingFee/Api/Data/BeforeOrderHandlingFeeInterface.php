<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Api\Data;

interface BeforeOrderHandlingFeeInterface extends OrderHandlingFeeInterface
{
    /**
     * @return float|null
     */
    public function getTotal(): ?float;

    /**
     * @param float|null $value
     */
    public function setTotal(?float $value): void;

    /**
     * @return float|null
     */
    public function getBaseTotal(): ?float;

    /**
     * @param float|null $value
     */
    public function setBaseTotal(?float $value): void;
}
