<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model;

use SwiftOtter\Catalog\Api\Data\IncomingPaymentPayloadInterface;

class IncomingPaymentPayload implements IncomingPaymentPayloadInterface
{
    /** @var string */
    private $description;

    /** @var string */
    private $nonce;

    /** @var string */
    private $type;

    /** @var string */
    private $cardType;

    /** @var string */
    private $lastFour;

    /** @var string[] */
    private $deviceData;

    /** @var string */
    private $hash;

    /** @var bool */
    private $liabilityShifted;

    /** @var bool */
    private $liabilityShiftPossible;

    /** @var int */
    private $expirationMonth;

    /** @var int */
    private $expirationYear;

    /** @var string */
    private $bin;

    /** @var string */
    private $lastTwo;

    /** @var string */
    private $email;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $payerId;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $businessName;

    /** @var array[] */
    private $shippingAddress;

    /** @var string */
    private $salutation;

    /** @var string */
    private $middleName;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return (string)$this->nonce;
    }

    /**
     * @param string $nonce
     */
    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCardType(): string
    {
        return (string)$this->cardType;
    }

    /**
     * @param string $cardType
     */
    public function setCardType(string $cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @return string
     */
    public function getLastFour(): string
    {
        return (string)$this->lastFour;
    }

    /**
     * @param string $lastFour
     */
    public function setLastFour(string $lastFour): void
    {
        $this->lastFour = $lastFour;
    }

    /**
     * @return string[]
     */
    public function getDeviceData(): array
    {
        return is_array($this->deviceData)
            ? $this->deviceData
            : [];
    }

    /**
     * @param string[] $deviceData
     */
    public function setDeviceData(array $deviceData): void
    {
        $this->deviceData = $deviceData;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return (int)$this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return bool
     */
    public function isLiabilityShifted(): bool
    {
        return (bool)$this->liabilityShifted;
    }

    /**
     * @param bool $liabilityShifted
     */
    public function setLiabilityShifted(bool $liabilityShifted): void
    {
        $this->liabilityShifted = $liabilityShifted;
    }

    /**
     * @return bool
     */
    public function isLiabilityShiftPossible(): bool
    {
        return (bool)$this->liabilityShiftPossible;
    }

    /**
     * @param bool $liabilityShiftPossible
     */
    public function setLiabilityShiftPossible(bool $liabilityShiftPossible): void
    {
        $this->liabilityShiftPossible = $liabilityShiftPossible;
    }

    /**
     * @return int
     */
    public function getExpirationMonth(): int
    {
        return (int)$this->expirationMonth;
    }

    /**
     * @param int $expirationMonth
     */
    public function setExpirationMonth(int $expirationMonth): void
    {
        $this->expirationMonth = $expirationMonth;
    }

    /**
     * @return int
     */
    public function getExpirationYear(): int
    {
        return (int)$this->expirationYear;
    }

    /**
     * @param int $expirationYear
     */
    public function setExpirationYear(int $expirationYear): void
    {
        $this->expirationYear = $expirationYear;
    }

    /**
     * @return string
     */
    public function getBin(): string
    {
        return (string)$this->bin;
    }

    /**
     * @param string $bin
     */
    public function setBin(string $bin): void
    {
        $this->bin = $bin;
    }

    /**
     * @return string
     */
    public function getLastTwo(): string
    {
        return (string)$this->lastTwo;
    }

    /**
     * @param string $lastTwo
     */
    public function setLastTwo(string $lastTwo): void
    {
        $this->lastTwo = $lastTwo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)$this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return (string)$this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return (string)$this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPayerId(): string
    {
        return (string)$this->payerId;
    }

    /**
     * @param string $payerId
     */
    public function setPayerId(string $payerId): void
    {
        $this->payerId = $payerId;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return (string)$this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getBusinessName(): string
    {
        return (string)$this->businessName;
    }

    /**
     * @param string $businessName
     */
    public function setBusinessName(string $businessName): void
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string[]
     */
    public function getShippingAddress(): array
    {
        return $this->shippingAddress ?? [];
    }

    /**
     * @param string[] $shippingAddress
     */
    public function setShippingAddress(array $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return string
     */
    public function getSalutation(): string
    {
        return (string)$this->salutation;
    }

    /**
     * @param string $salutation
     * @return void
     */
    public function setSalutation(string $salutation): void
    {
        $this->salutation = $salutation;
    }

    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getMiddleName(): string
    {
        return (string)$this->middleName;
    }
}