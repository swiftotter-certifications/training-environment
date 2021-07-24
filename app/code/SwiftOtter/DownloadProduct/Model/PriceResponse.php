<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2019/03/26
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use SwiftOtter\Catalog\Model\PriceCalculator;
use SwiftOtter\DownloadProduct\Api\PriceResponseInterface;
use SwiftOtter\DownloadProduct\Endpoint\Token;

class PriceResponse implements PriceResponseInterface
{
    private $price;

    private $basePrice;

    /** @var PriceCurrencyInterface */
    private $pricingHelper;

    /** @var string */
    private $name;

    /** @var string */
    private $sku;

    /** @var Token */
    private $token;

    /** @var string */
    private $currency;

    /** @var bool */
    private $couponIsApplied;

    /** @var PriceCalculator|null */
    private $priceCalculator;

    public function __construct(
        string $name,
        string $sku,
        PriceCurrencyInterface $pricingHelper,
        PriceCalculator $priceCalculator
    ) {
        $this->pricingHelper = $pricingHelper;
        $this->name = $name;
        $this->sku = $sku;
        $this->priceCalculator = $priceCalculator;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormattedPrice(): string
    {
        return (string)$this->pricingHelper->format($this->getPrice(), false, null, null, $this->getCurrency());
    }

    public function getFormattedPriceWithTax(): string
    {
        return (string)$this->pricingHelper->format($this->getPrice() + $this->getTaxAmount(), false, null, null, $this->getCurrency());
    }

    public function getFormattedTaxAmount(): string
    {
        return (string)$this->pricingHelper->format($this->getTaxAmount(), false, null, null, $this->getCurrency());
    }

    public function getTaxAmount(): ?float
    {
        return $this->priceCalculator->getTaxAmount();
    }

    public function getBaseTaxAmount(): ?float
    {
        return $this->priceCalculator->getBaseTaxAmount();
    }

    public function getPrice(): float
    {
        return $this->priceCalculator->getPrice();
    }

    public function getBasePrice(): float
    {
        return $this->priceCalculator->getBasePrice();
    }

    public function getToken(): string
    {
        return ''; //$this->token->get();
    }

    public function getCurrency(): string
    {
        return $this->priceCalculator->getCurrencyCode();
    }

    public function getCouponIsApplied(): bool
    {
        return $this->priceCalculator->getCouponIsApplied();
    }
}
