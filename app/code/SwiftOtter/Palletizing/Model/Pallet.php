<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use SwiftOtter\Palletizing\Api\Data\AfterOrderPalletInterface;
use SwiftOtter\Palletizing\Api\Data\BeforeOrderPalletInterface;
use SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterface;
use SwiftOtter\Palletizing\Api\Data\OrderPalletInterface;
use SwiftOtter\Palletizing\Model\ResourceModel\Pallet as PalletResourceModel;

class Pallet extends AbstractExtensibleModel implements BeforeOrderPalletInterface, AfterOrderPalletInterface
{
    protected function _construct()
    {
        $this->_init(PalletResourceModel::class);
    }

    public function getId(): ?int
    {
        return $this->nullableInt($this->getData('id'));
    }

    public function setId($value): void
    {
        $this->setData('id', $value);
    }

    public function getInvoiced(): ?float
    {
        return $this->nullableFloat($this->getData('invoiced'));
    }

    public function setInvoiced(?float $value): void
    {
        $this->setData('invoiced', $value);
    }

    public function getBaseInvoiced(): ?float
    {
        return $this->nullableFloat($this->getData('base_invoiced'));
    }

    public function setBaseInvoiced(?float $value): void
    {
        $this->setData('base_invoiced', $value);
    }

    public function getCredited(): ?float
    {
        return $this->nullableFloat($this->getData('credited'));
    }

    public function setCredited(?float $value): void
    {
        $this->setData('credited', $value);
    }

    public function getBaseCredited(): ?float
    {
        return $this->nullableFloat($this->getData('based_credited'));
    }

    public function setBaseCredited(?float $value): void
    {
        $this->setData('based_credited', $value);
    }

    public function getTotal(): ?float
    {
        return $this->nullableFloat($this->getData('total'));
    }

    public function setTotal(?float $value): void
    {
        $this->setData('total', $value);
    }

    public function getBaseTotal(): ?float
    {
        return $this->nullableFloat($this->getData('base_total'));
    }

    public function setBaseTotal(?float $value): void
    {
        $this->setData('base_total', $value);
    }

    private function nullableFloat($incoming): ?float
    {
        return $incoming ? (float)$incoming : null;
    }

    private function nullableInt($incoming): ?float
    {
        return $incoming ? (int)$incoming : null;
    }

    public function getExtensionAttributes(): ?OrderPalletExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(OrderPalletExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }


}