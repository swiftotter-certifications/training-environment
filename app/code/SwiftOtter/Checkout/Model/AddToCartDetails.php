<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SwiftOtter\Catalog\Api\Data\IncomingShareRequestInterface as IncomingShareRequest;
use SwiftOtter\Catalog\Model\ResourceModel\ProductLookup;
use SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface;

class AddToCartDetails extends AbstractExtensibleModel implements AddToCartDetailsInterface
{
    private $sku;

    private $qty;

    private $children = [];

    private $share;

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getQty(): int
    {
        return $this->qty ?: 1;
    }

    public function setQty(int $qty)
    {
        $this->qty = max($qty, 1);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    public function getShare(): ?IncomingShareRequest
    {
        return $this->share;
    }

    public function setShare(IncomingShareRequest $request): void
    {
        $this->share = $request;
    }

    public function getExtensionAttributes(): \SwiftOtter\Checkout\Api\Data\AddToCartDetailsExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(array $extensionAttributes): void
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
