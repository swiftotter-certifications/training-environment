<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Plugin;

use SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterfaceFactory as ExtensionFactory;
use SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeInterface;
use SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeInterfaceFactory as Factory;

class EnsureExtensionAttributesExistForHandlingFee
{
    /** @var ExtensionFactory */
    private $extensionFactory;

    public function __construct(ExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function afterCreate(Factory $subject, OrderHandlingFeeInterface $handlingFee)
    {
        $handlingFee->setExtensionAttributes(
            $handlingFee->getExtensionAttributes() ?: $this->extensionFactory->create()
        );

        return $handlingFee;
    }
}
