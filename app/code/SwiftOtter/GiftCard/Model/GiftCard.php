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
        $this->setData(self::ASSIGNED_CUSTOMER_ID, $value);
    }

    public function getCustomerId(): ?int
    {
        return (int)$this->getData(self::ASSIGNED_CUSTOMER_ID);
    }

    public function setCode(string $value): void
    {
        $this->setData(self::CODE, $value);
    }

    public function getCode(): ?string
    {
        return (string)$this->getData(self::CODE);
    }

    public function setStatus(int $value): void
    {
        $this->setData(self::STATUS, $value);
    }

    public function getStatus(): ?int
    {
        return (int)$this->getData(self::STATUS);
    }

    public function setInitialValue(float $value): void
    {
        $this->setData(self::INITIAL_VALUE, $value);
    }

    public function getInitialValue(): ?float
    {
        return (float)$this->getData(self::INITIAL_VALUE);
    }

    public function setCurrentValue(float $value): void
    {
        $this->setData(self::CURRENT_VALUE, $value);
    }

    public function getCurrentValue(): ?float
    {
        return (float)$this->getData(self::CURRENT_VALUE);
    }

    public function setCreatedAt(?string $value): void
    {
        $this->setData(self::CREATED_AT, $value);
    }

    public function getCreatedAt(): ?string
    {
        return$this->getData(self::CREATED_AT);
    }

    public function setUpdatedAt(string $value): void
    {
        $this->setData(self::UPDATED_AT, $value);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setRecipientEmail(string $value): void
    {
        $this->setData(self::RECIPIENT_EMAIL, $value);
    }

    public function getRecipientEmail(): ?string
    {
        return (string)$this->getData(self::RECIPIENT_EMAIL);
    }

    public function setRecipientName(string $value): void
    {
        $this->setData(self::RECIPIENT_NAME, $value);
    }

    public function getRecipientName(): ?string
    {
        return (string)$this->getData(self::RECIPIENT_NAME);
    }

    public function setDisableNotification(bool $value): void
    {
        $this->setData(self::DISABLE_NOTIFICATION, $value);
    }

    public function getDisableNotification(): bool
    {
        return (bool)$this->getData(self::DISABLE_NOTIFICATION);
    }
}
