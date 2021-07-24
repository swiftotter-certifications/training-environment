<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Api\Data;

interface IncomingAddressInterface
{
    /**
     * @return string
     */
    public function getVatId(): string;

    /**
     * @param string $vatId
     */
    public function setVatId(string $vatId): void;

    /**
     * @return string
     */
    public function getCompany(): string;

    /**
     * @param string $company
     */
    public function setCompany(string $company): void;

    /**
     * @return string
     */
    public function getTelephone(): string;

    /**
     * @param string $telephone
     */
    public function setTelephone(string $telephone): void;

    /**
     * @return int
     */
    public function getAddressId(): int;

    /**
     * @param string $addressId
     */
    public function setAddressId(string $addressId): void;

    /**
     * @return string
     */
    public function getStreet(): string;

    /**
     * @param string $street
     */
    public function setStreet(string $street): void;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @param string $city
     */
    public function setCity(string $city): void;

    /**
     * @return string
     */
    public function getRegion(): string;

    /**
     * @param string $region
     */
    public function setRegion(string $region): void;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void;

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @param string $country
     */
    public function setCountry(string $country): void;
}