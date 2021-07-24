<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model;

use SwiftOtter\Catalog\Api\Data\IncomingAddressInterface;

class IncomingAddress implements IncomingAddressInterface
{
    /** @var string */
    private $vatId;

    /** @var string */
    private $company;

    /** @var string */
    private $telephone;

    /** @var int */
    private $addressId;

    /** @var string */
    private $street;

    /** @var string */
    private $city;

    /** @var string */
    private $region;

    /** @var string */
    private $country;

    /** @var string */
    private $postalCode;

    /**
     * @return string
     */
    public function getVatId(): string
    {
        return (string)$this->vatId;
    }

    /**
     * @param string $vatId
     */
    public function setVatId(string $vatId): void
    {
        $this->vatId = $vatId;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return (string)$this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getTelephone(): string
    {
        return (string)$this->telephone;
    }

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getAddressId(): int
    {
        return (int)$this->addressId;
    }

    public function setAddressId(string $addressId): void
    {
        $this->addressId = $addressId;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return (string)$this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return (string)$this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return (string)$this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return (string)$this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return (string)$this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}