<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

interface OrderExportDetailsInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return string
     */
    public function getShipOn(): string;

    /**
     * @param string $shipOn
     */
    public function setShipOn(string $shipOn): void;

    /**
     * @return string
     */
    public function getExportedAt(): string;

    /**
     * @param string $exportedAt
     */
    public function setExportedAt(string $exportedAt): void;

    /**
     * @return string
     */
    public function getMerchantNotes(): string;

    /**
     * @param string $merchantNotes
     */
    public function setMerchantNotes(string $merchantNotes): void;

    /**
     * @return bool
     */
    public function hasBeenExported(): bool;
}
