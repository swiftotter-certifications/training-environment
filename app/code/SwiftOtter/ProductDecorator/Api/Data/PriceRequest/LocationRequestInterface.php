<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PriceRequest;

interface LocationRequestInterface
{
    /**
     * @return int
     */
    public function getLocationId(): ?int;

    /**
     * @param int $locationId
     * @return void;
     */
    public function setLocationId(int $locationId): void;

    /**
     * @param string[] $colors
     * @return void
     */
    public function setColors(array $colors): void;

    /**
     * @return string[]
     */
    public function getColors(): ?array;

    /**
     * @return int|null
     */
    public function getPrintMethodId(): ?int;

    /**
     * @param int $value
     */
    public function setPrintMethodId(int $value): void;
}
