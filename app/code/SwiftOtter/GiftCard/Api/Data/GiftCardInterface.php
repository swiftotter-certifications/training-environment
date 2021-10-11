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

    /**
     * @param $id
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param int $customerId
     */
    public function setCustomerId(int $customerId): void;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param string $code
     */
    public function setCode(string $code): void;

    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @param int $status
     */
    public function setStatus(int $status): void;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @param float $value
     */
    public function setInitialValue(float $value): void;

    /**
     * @return float|null
     */
    public function getInitialValue(): ?float;

    /**
     * @param float $value
     */
    public function setCurrentValue(float $value): void;

    /**
     * @return float|null
     */
    public function getCurrentValue(): ?float;

    /**
     * @param \DateTime $value
     */
    public function setCreatedAt(\DateTime $value): void;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @param \DateTime $value
     */
    public function setUpdatedAt(\DateTime $value): void;

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime;

    /**
     * @param string $value
     */
    public function setRecipientEmail(string $value): void;

    /**
     * @return string|null
     */
    public function getRecipientEmail(): ?string;

    /**
     * @param string $value
     */
    public function setRecipientName(string $value): void;

    /**
     * @return string|null
     */
    public function getRecipientName(): ?string;
}
