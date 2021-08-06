<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Quote\Api\Data\EstimateAddressInterface;

interface OrderHandlingFeeInterface extends ExtensibleDataInterface
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
     * @return \SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterface|null
     */
    public function getExtensionAttributes() : ?\SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterface $extensionAttributes
    );
}
