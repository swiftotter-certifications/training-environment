<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResource;

class GiftCard extends AbstractModel implements GiftCardInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_USED = 2;

    protected $_eventPrefix = 'giftcard';

    protected function _construct()
    {
        $this->_init(GiftCardResource::class);
    }

    public function setCustomerId(int $value): void
    {
        $this->setData('customer_id', $value);
    }

    public function getCustomerId(): ?int
    {
        return (int)$this->getData('customer_id');
    }

    public function setCode(string $value): void
    {
        $this->setData('code', $value);
    }

    public function getCode(): ?string
    {
        return (string)$this->getData('code');
    }

    public function setStatus(int $value): void
    {
        $this->setData('status', $value);
    }

    public function getStatus(): ?int
    {
        return (int)$this->getData('status');
    }

    public function setInitialValue(float $value): void
    {
        $this->setData('initial_value', $value);
    }

    public function getInitialValue(): ?float
    {
        return (float)$this->getData('initial_value');
    }

    public function setCurrentValue(float $value): void
    {
        $this->setData('current_value', $value);
    }

    public function getCurrentValue(): ?float
    {
        return (float)$this->getData('current_value');
    }

    public function setCreatedAt(\DateTime $value): void
    {
        $this->setData('created_at', $value->format('Y-m-d h:i:s'));
    }

    public function getCreatedAt(): ?\DateTime
    {
        return new \DateTime($this->getData('created_at'));
    }

    public function setUpdatedAt(\DateTime $value): void
    {
        $this->setData('updated_at', $value->format('Y-m-d h:i:s'));
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return new \DateTime($this->getData('updated_at'));
    }

    public function setRecipientEmail(string $value): void
    {
        $this->setData('recipient_email', $value);
    }

    public function getRecipientEmail(): ?string
    {
        return (string)$this->getData('recipient_email');
    }

    public function setRecipientName(string $value): void
    {
        $this->setData('recipient_name', $value);
    }

    public function getRecipientName(): ?string
    {
        return (string)$this->getData('recipient_value');
    }
}