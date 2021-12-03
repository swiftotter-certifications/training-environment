<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc., 2019/03/26
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use SwiftOtter\Catalog\Model\PriceCalculator;
use SwiftOtter\DownloadProduct\Api\PriceResponseInterface;
use SwiftOtter\DownloadProduct\Endpoint\Token;
use SwiftOtter\Utils\Model\ResourceModel\ProductLookup;

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

    private ProductResource $productResource;
    private ProductLookup $productLookup;

    public function __construct(
        string $name,
        string $sku,
        PriceCurrencyInterface $pricingHelper,
        ProductResource $productResource,
        ProductLookup $productLookup
    ) {
        $this->pricingHelper = $pricingHelper;
        $this->name = $name;
        $this->sku = $sku;
        $this->productResource = $productResource;
        $this->productLookup = $productLookup;
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
        return 0;
    }

    public function getBaseTaxAmount(): ?float
    {
        return 0;
    }

    public function getPrice(): float
    {
        return (float)$this->productResource->getAttributeRawValue($this->productLookup->getEntityIdFromSku($this->sku), 'price', 0);
    }

    public function getBasePrice(): float
    {
        return (float)$this->productResource->getAttributeRawValue($this->productLookup->getEntityIdFromSku($this->sku), 'price', 0);
    }

    public function getToken(): string
    {
        return ''; //$this->token->get();
    }

    public function getCurrency(): string
    {
        return 'USD';
    }

    public function getCouponIsApplied(): bool
    {
        return false;
    }
}
