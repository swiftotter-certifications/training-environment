<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PrintSpec;

use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;

interface LocationInterface extends LocationRequestInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param $id
     * @return void
     */
    public function setId($id): void;
}
