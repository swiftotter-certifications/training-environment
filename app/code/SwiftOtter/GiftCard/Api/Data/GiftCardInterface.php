<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api\Data;

interface GiftCardInterface
{
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