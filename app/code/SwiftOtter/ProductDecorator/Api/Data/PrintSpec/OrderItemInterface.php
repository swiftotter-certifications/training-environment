<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api\Data\PrintSpec;

interface OrderItemInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int|null
     */
    public function getOrderItemId(): ?int;

    /**
     * @return int|null
     */
    public function getPrintSpecId(): ?int;

    /**
     * @param $id
     */
    public function setId($id): void;

    /**
     * @param int|null $value
     */
    public function setOrderItemId(?int $value): void;

    /**
     * @param int|null $value
     */
    public function setPrintSpecId(?int $value): void;
}
