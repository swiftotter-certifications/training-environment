<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api\Data;

interface GiftCardInterface
{
    const ID = 'id';
    const ASSIGNED_CUSTOMER_ID = 'assigned_customer_id';
    const CODE = 'code';
    const STATUS = 'status';
    const INITIAL_VALUE = 'initial_value';
    const CURRENT_VALUE = 'current_value';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const RECIPIENT_NAME = 'recipient_name';
    const RECIPIENT_EMAIL = 'recipient_email';
    const DISABLE_NOTIFICATION = 'disable_notification';

    /**
     * @param $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $customerId
     * @return void
     */
    public function setCustomerId(int $customerId): void;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void;

    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @param int $status
     * @return void
     */
    public function setStatus(int $status): void;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @param float $value
     * @return void;
     */
    public function setInitialValue(float $value): void;

    /**
     * @return float|null
     */
    public function getInitialValue(): ?float;

    /**
     * @param float $value
     * @return void
     */
    public function setCurrentValue(float $value): void;

    /**
     * @return float|null
     */
    public function getCurrentValue(): ?float;

    /**
     * @param string $value
     * @return void
     */
    public function setCreatedAt(string $value): void;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $value
     * @return void
     */
    public function setUpdatedAt(string $value): void;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $value
     * @return void
     */
    public function setRecipientEmail(string $value): void;

    /**
     * @return string|null
     */
    public function getRecipientEmail(): ?string;

    /**
     * @param string $value
     * @return void
     */
    public function setRecipientName(string $value): void;

    /**
     * @return string|null
     */
    public function getRecipientName(): ?string;

    /**
     * @param bool $value
     */
    public function setDisableNotification(bool $value): void;

    /**
     * @return bool
     */
    public function getDisableNotification(): bool;
}
