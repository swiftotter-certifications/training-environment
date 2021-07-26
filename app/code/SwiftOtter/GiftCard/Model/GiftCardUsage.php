<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResourceModel;

class GiftCardUsage extends AbstractModel implements GiftCardUsageInterface
{
    protected function _construct()
    {
        $this->_init(GiftCardUsageResourceModel::class);
    }

    public function getGiftCardId(): ?int
    {
        return (int)$this->getData('gift_card_id');
    }

    public function setGiftCardId(int $value): void
    {
        $this->setData('gift_card_id', $value);
    }

    public function getOrderId(): ?int
    {
        return (int)$this->getData('order_id');
    }

    public function setOrderId(int $value): void
    {
        $this->setData('order_id', $value);
    }

    public function getValueChange(): ?float
    {
        return (float)$this->getData('value_change');
    }

    public function setValueChange(float $value): void
    {
        $this->setData('value_change', $value);
    }

    public function getNotes(): ?string
    {
        return (string)$this->getData('notes');
    }

    public function setNotes(string $value): void
    {
        $this->setData('notes', $value);
    }

    public function getCreatedAt(): ?\DateTime
    {
        return new \DateTime($this->getData('customer_id'));
    }

    public function setCreatedAt(\DateTime $value): void
    {
        $this->setData('created_at', $value->format('Y-m-d h:i:s'));
    }
}