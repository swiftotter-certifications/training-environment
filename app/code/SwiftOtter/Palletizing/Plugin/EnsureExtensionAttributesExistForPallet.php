<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Plugin;

use SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterfaceFactory as ExtensionFactory;
use SwiftOtter\Palletizing\Api\Data\OrderPalletInterface;
use SwiftOtter\Palletizing\Api\Data\OrderPalletInterfaceFactory as Factory;

class EnsureExtensionAttributesExistForPallet
{
    /** @var ExtensionFactory */
    private $extensionFactory;

    public function __construct(ExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function afterCreate(Factory $subject, OrderPalletInterface $pallet)
    {
        $pallet->setExtensionAttributes(
            $pallet->getExtensionAttributes() ?: $this->extensionFactory->create()
        );

        return $pallet;
    }
}