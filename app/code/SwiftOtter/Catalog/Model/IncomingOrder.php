<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model;

use SwiftOtter\Catalog\Api\Data\IncomingAddressInterface;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;
use SwiftOtter\Catalog\Api\Data\IncomingPaymentPayloadInterface;
use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface;

class IncomingOrder implements IncomingOrderDetailsInterface
{
    /** @var IncomingPaymentPayloadInterface */
    private $payload;

    /** @var string */
    private $email;

    /** @var string */
    private $name;

    /** @var string */
    private $postalCode;

    /** @var string */
    private $choice;

    /** @var IncomingShareRequestInterface */
    private $share;

    /** @var IncomingAddressInterface */
    private $address;

    /** @var bool */
    private $signup;

    /** @var string */
    private $userToken;

    /** @var int */
    private $productSku;

    /** @var string */
    private $type;

    /** @var string */
    private $currency;

    /** @var string */
    private $bodyClasses;

    /** @var string */
    private $discount;

    /** @var int */
    private $quantity;

    /** @var string */
    private $recaptchaKey;

    /**
     * @return IncomingPaymentPayloadInterface
     */
    public function getPayload(): ?IncomingPaymentPayloadInterface
    {
        return $this->payload;
    }

    /**
     * @param IncomingPaymentPayloadInterface $payload
     */
    public function setPayload(IncomingPaymentPayloadInterface $payload): void
    {
        $this->payload = $payload;
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
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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

    /**
     * @return IncomingAddressInterface
     */
    public function getAddress(): ?IncomingAddressInterface
    {
        return $this->address;
    }

    /**
     * @param IncomingAddressInterface $address
     */
    public function setAddress(IncomingAddressInterface $address): void
    {
        $this->address = $address;
    }

    /**
     * @return bool
     */
    public function isSignup(): bool
    {
        return (bool)$this->signup;
    }

    /**
     * @param bool $signup
     */
    public function setSignup(bool $signup): void
    {
        $this->signup = $signup;
    }

    /**
     * @return string
     */
    public function getProductIdentifier(): string
    {
        return (string)$this->productSku;
    }

    /**
     * @param string $productSku
     */
    public function setProductIdentifier(string $productSku): void
    {
        $this->productSku = $productSku;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return (string)$this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getBodyClasses(): string
    {
        return (string)$this->bodyClasses;
    }

    /**
     * @param string $bodyClasses
     */
    public function setBodyClasses(string $bodyClasses): void
    {
        $this->bodyClasses = $bodyClasses;
    }

    /**
     * @return string
     */
    public function getDiscount(): string
    {
        return (string)$this->discount;
    }

    /**
     * @param string $discount
     */
    public function setDiscount(string $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity ?? 1;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getRecaptchaKey(): string
    {
        return (string)$this->recaptchaKey;
    }

    /**
     * @param string $recaptchaKey
     */
    public function setRecaptchaKey(string $recaptchaKey): void
    {
        $this->recaptchaKey = $recaptchaKey;
    }

    public function getShare(): ?IncomingShareRequestInterface
    {
        return $this->share;
    }

    public function setShare(IncomingShareRequestInterface $share): void
    {
        $this->share = $share;
    }

    /**
     * @return string
     */
    public function getChoice(): string
    {
        return $this->choice ?? '';
    }

    /**
     * @param string $choice
     */
    public function setChoice(string $choice): void
    {
        $this->choice = $choice;
    }

    /**
     * @return string
     */
    public function getUserToken(): string
    {
        return $this->userToken ?? '';
    }

    /**
     * @param string $userToken
     */
    public function setUserToken(string $userToken): void
    {
        $this->userToken = $userToken;
    }
}
