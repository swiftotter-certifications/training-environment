<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;

class PriceRequest extends AbstractExtensibleModel implements PriceRequestInterface
{
    private $clientId;

    private $products;

    private $locations;

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setProducts(array $products): void
    {
        $this->products = [];
    }

    public function getProducts(): array
    {
        return $this->products ?? [];
    }

    public function setLocations(array $locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations(): array
    {
        return $this->locations ?? [];
    }

    public function getTotalQuantity(): int
    {
        return array_reduce($this->products, function(float $total, ProductRequestInterface $product) {
            return $total + (float)$product->getQuantity();
        }, 0);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(\SwiftOtter\ProductDecorator\Api\Data\PriceRequestExtensionInterface $extensionAttributes)
    {
        $this->setExtensionAttributes($extensionAttributes);
    }
}
