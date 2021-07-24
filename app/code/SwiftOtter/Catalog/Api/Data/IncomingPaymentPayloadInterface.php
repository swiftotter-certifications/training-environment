<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Api\Data;

/**
 * @see PAYMENT_PAYLOAD_ALLOWED_KEYS in ui/src/store/purchase/constants.js
 */
interface IncomingPaymentPayloadInterface
{
    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     */
    public function setDescription(string $description): void;

    /**
     * @return string
     */
    public function getNonce(): string;

    /**
     * @param string $nonce
     */
    public function setNonce(string $nonce): void;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     */
    public function setType(string $type): void;

    /**
     * @return string
     */
    public function getCardType(): string;

    /**
     * @param string $cardType
     */
    public function setCardType(string $cardType): void;

    /**
     * @return string
     */
    public function getLastFour(): string;

    /**
     * @param string $lastFour
     */
    public function setLastFour(string $lastFour): void;

    /**
     * @return string[]
     */
    public function getDeviceData(): array;

    /**
     * @param string[] $deviceData
     */
    public function setDeviceData(array $deviceData): void;

    /**
     * @return bool
     */
    public function isLiabilityShifted(): bool;

    /**
     * @param bool $liabilityShifted
     */
    public function setLiabilityShifted(bool $liabilityShifted): void;

    /**
     * @return bool
     */
    public function isLiabilityShiftPossible(): bool;

    /**
     * @param bool $liabilityShiftPossible
     */
    public function setLiabilityShiftPossible(bool $liabilityShiftPossible): void;

    /**
     * @return int
     */
    public function getExpirationMonth(): int;

    /**
     * @param int $expirationMonth
     */
    public function setExpirationMonth(int $expirationMonth): void;

    /**
     * @return int
     */
    public function getExpirationYear(): int;

    /**
     * @param int $expirationYear
     */
    public function setExpirationYear(int $expirationYear): void;

    /**
     * @return string
     */
    public function getBin(): string;

    /**
     * @param string $bin
     */
    public function setBin(string $bin): void;

    /**
     * @return string
     */
    public function getLastTwo(): string;

    /**
     * @param string $lastTwo
     */
    public function setLastTwo(string $lastTwo): void;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void;

    /**
     * @return string
     */
    public function getPayerId(): string;

    /**
     * @param string $payerId
     */
    public function setPayerId(string $payerId): void;

    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void;

    /**
     * @return string
     */
    public function getBusinessName(): string;

    /**
     * @param string $businessName
     */
    public function setBusinessName(string $businessName): void;

    /**
     * @return string[]
     */
    public function getShippingAddress(): array;

    /**
     * @param string[] $shippingAddress
     */
    public function setShippingAddress(array $shippingAddress): void;

    /**
     * @param string $salutation
     * @return void
     */
    public function setSalutation(string $salutation): void;

    /**
     * @return string
     */
    public function getSalutation(): string;

    /**
     * @param string $middleName
     * @return void
     */
    public function setMiddleName(string $middleName): void;

    /**
     * @return string
     */
    public function getMiddleName(): string;

}
