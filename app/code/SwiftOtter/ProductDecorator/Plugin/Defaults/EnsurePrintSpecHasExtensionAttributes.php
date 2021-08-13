<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/12/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Defaults;

use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface as Target;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceExtensionFactory as ExtensionFactory;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceFactory as TargetFactory;

class EnsurePrintSpecHasExtensionAttributes
{
    /** @var ExtensionFactory */
    private $extensionFactory;

    public function __construct(ExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    public function afterCreate(TargetFactory $targetFactory, Target $target)
    {
        $target->setExtensionAttributes(
            $target->getExtensionAttributes() ?: $this->extensionFactory->create()
        );

        return $target;
    }
}
