<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data;

interface LocationPrintMethodInterface
{
    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return int
     */
    public function getLocationId(): int;

    /**
     * @return int
     */
    public function getPrintMethodId(): int;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void;

    /**
     * @param int $locationId
     * @return void
     */
    public function setLocationId(int $locationId): void;

    /**
     * @param int $printMethodId
     * @return void
     */
    public function setPrintMethodId(int $printMethodId): void;

    /**
     * @param string $sku
     * @return void
     */
    public function setSku(string $sku): void;
}
