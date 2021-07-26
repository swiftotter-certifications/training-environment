<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api\Data;

interface GiftCardUsageInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     */
    public function setId($id);

    /**
     * @return int|null
     */
    public function getGiftCardId(): ?int;

    /**
     * @param int $value
     */
    public function setGiftCardId(int $value): void;

    /**
     * @return int|null
     */
    public function getOrderId(): ?int;

    /**
     * @param int $value
     */
    public function setOrderId(int $value): void;

    /**
     * @return float|null
     */
    public function getValueChange(): ?float;

    /**
     * @param float $value
     */
    public function setValueChange(float $value): void;

    /**
     * @return string|null
     */
    public function getNotes(): ?string;

    /**
     * @param string $value
     */
    public function setNotes(string $value): void;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @param \DateTime $value
     */
    public function setCreatedAt(\DateTime $value): void;
}