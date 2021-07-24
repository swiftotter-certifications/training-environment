<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Api\Data;

/**
 * @see ORDER_ALLOWED_KEYS in ui/src/store/purchase/constants.js
 */
interface IncomingOrderDetailsInterface
{
    /**
     * @return \SwiftOtter\Catalog\Api\Data\IncomingPaymentPayloadInterface
     */
    public function getPayload(): ?IncomingPaymentPayloadInterface;

    /**
     * @param \SwiftOtter\Catalog\Api\Data\IncomingPaymentPayloadInterface $payload
     */
    public function setPayload(IncomingPaymentPayloadInterface $payload): void;

    /**
     * @return \SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface
     */
    public function getShare(): ?IncomingShareRequestInterface;

    /**
     * @param \SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface $share
     */
    public function setShare(IncomingShareRequestInterface $share): void;

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
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getPostalCode(): string;

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void;

    /**
     * @return \SwiftOtter\Catalog\Api\Data\IncomingAddressInterface
     */
    public function getAddress(): ?IncomingAddressInterface;

    /**
     * @param \SwiftOtter\Catalog\Api\Data\IncomingAddressInterface $address
     */
    public function setAddress(IncomingAddressInterface $address): void;

    /**
     * @return bool
     */
    public function isSignup(): bool;

    /**
     * @param bool $signup
     */
    public function setSignup(bool $signup): void;

    /**
     * @return string
     */
    public function getProductIdentifier(): string;

    /**
     * @param string $productString
     */
    public function setProductIdentifier(string $productString): void;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void;

    /**
     * @return string
     */
    public function getBodyClasses(): string;

    /**
     * @param string $bodyClasses
     */
    public function setBodyClasses(string $bodyClasses): void;

    /**
     * @return string
     */
    public function getDiscount(): string;

    /**
     * @param string $discount
     */
    public function setDiscount(string $discount): void;

    /**
     * @return int
     */
    public function getQuantity(): int;

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void;

    /**
     * @return string
     */
    public function getRecaptchaKey(): string;

    /**
     * @param string $key
     */
    public function setRecaptchaKey(string $key): void;

    /**
     * @return string
     */
    public function getChoice(): string;

    /**
     * @param string $choice
     */
    public function setChoice(string $choice): void;

    /**
     * @return string
     */
    public function getUserToken(): string;

    /**
     * @param string $userToken
     */
    public function setUserToken(string $userToken): void;
}
