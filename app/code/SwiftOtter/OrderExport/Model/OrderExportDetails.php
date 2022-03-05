<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsInterface;

class OrderExportDetails extends AbstractModel implements OrderExportDetailsInterface
{
    protected function _construct()
    {
        $this->_init(\SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails::class);
    }

    public function getOrderId(): ?int
    {
        return (int)$this->getData('order_id');
    }

    public function setOrderId(int $orderId): void
    {
        $this->setData('order_id', $orderId);
    }

    public function getShipOn(): ?string
    {
        return $this->getData('ship_on');
    }

    public function setShipOn(string $shipOn): void
    {
        $this->setData('ship_on', $shipOn);
    }

    public function getExportedAt(): ?string
    {
        return $this->getData('exported_at');
    }

    public function setExportedAt(string $exportedAt): void
    {
        $this->setData('exported_at', $exportedAt);
    }

    public function hasBeenExported(): bool
    {
        return (bool)$this->getData('exported_at');
    }

    public function getMerchantNotes(): string
    {
        return (string)$this->getData('merchant_notes');
    }

    public function setMerchantNotes(string $merchantNotes): void
    {
        $this->setData('merchant_notes', $merchantNotes);
    }

    public function getCustomAttributes(): array
    {
        return $this->getData();
    }
}
