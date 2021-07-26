<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Quote\Api\Data\EstimateAddressInterface;

interface OrderPalletInterface extends ExtensibleDataInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int $value
     */
    public function setId($value): void;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterface|null
     */
    public function getExtensionAttributes() : ?\SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterface $extensionAttributes
    );
}