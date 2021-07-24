<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 9/17/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Api\Data;

interface OrderUserInformationInterface
{
    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return int
     */
    public function getOrderItemId(): int;

    /**
     * @param int $orderItemId
     */
    public function setOrderItemId(int $orderItemId): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return bool
     */
    public function getIsShared(): bool;

    /**
     * @param bool $value
     */
    public function setIsShared(bool $value): void;
}
