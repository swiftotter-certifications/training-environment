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
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * @param int $orderId
     * @return void
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return string|null
     */
    public function getShipOn(): ?string;

    /**
     * @param string $shipOn
     * @return void
     */
    public function setShipOn(string $shipOn): void;

    /**
     * @return string|null
     */
    public function getExportedAt(): ?string;

    /**
     * @param string $exportedAt
     * @return void
     */
    public function setExportedAt(string $exportedAt): void;

    /**
     * @return string
     */
    public function getMerchantNotes(): string;

    /**
     * @param string $merchantNotes
     * @return void
     */
    public function setMerchantNotes(string $merchantNotes): void;

    /**
     * @return bool
     */
    public function hasBeenExported(): bool;
}
