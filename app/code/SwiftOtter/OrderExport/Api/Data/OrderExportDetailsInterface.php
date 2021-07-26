<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api\Data;

interface OrderExportDetailsInterface
{
    public function getId();

    public function getOrderId(): int;

    public function setOrderId(int $orderId): void;

    public function getShipOn(): \DateTime;

    public function setShipOn(\DateTime $shipOn): void;

    public function getExportedAt(): \DateTime;

    public function setExportedAt(\DateTime $exportedAt): void;

    public function getMerchantNotes(): string;

    public function setMerchantNotes(string $merchantNotes): void;

    public function hasBeenExported(): bool;
}