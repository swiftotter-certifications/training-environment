<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;

class PriceRequestLocation implements LocationRequestInterface
{
    private $locationId;

    private $colors;

    private $printMethodId;

    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): void
    {
        $this->locationId = $locationId;
    }

    public function setColors(array $colors): void
    {
        $this->colors = $colors;
    }

    public function getColors(): ?array
    {
        return $this->colors;
    }

    public function getPrintMethodId(): ?int
    {
        return $this->printMethodId;
    }

    public function setPrintMethodId(int $value): void
    {
        $this->printMethodId = $value;
    }
}
