<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Api\Data;

interface AfterOrderHandlingFeeInterface extends OrderHandlingFeeInterface
{
    /**
     * @return float|null
     */
    public function getInvoiced(): ?float;

    /**
     * @param float|null $value
     * @return void
     */
    public function setInvoiced(?float $value): void;

    /**
     * @return float|null
     */
    public function getBaseInvoiced(): ?float;

    /**
     * @param float|null $value
     */
    public function setBaseInvoiced(?float $value): void;

    /**
     * @return float|null
     */
    public function getCredited(): ?float;

    /**
     * @param float|null $value
     */
    public function setCredited(?float $value): void;

    /**
     * @return float|null
     */
    public function getBaseCredited(): ?float;

    /**
     * @param float|null $value
     */
    public function setBaseCredited(?float $value): void;
}
