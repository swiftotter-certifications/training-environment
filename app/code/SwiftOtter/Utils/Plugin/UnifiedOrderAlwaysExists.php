<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use SwiftOtter\Utils\Model\UnifiedSaleFactory as UnifiedSaleFactory;
use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderExtensionInterface as OrderExtension;
use Magento\Sales\Api\Data\OrderExtensionInterfaceFactory as OrderExtensionFactory;
use Magento\Order\Model\Order;

class UnifiedOrderAlwaysExists
{
    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /** @var UnifiedSaleItemFactory */
    private $unifiedSaleFactory;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        UnifiedSaleFactory $unifiedSaleFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->unifiedSaleFactory = $unifiedSaleFactory;
    }

    public function afterCreate($subject, $result)
    {
        if (!($result instanceof Order)) {
            return $result;
        }

        /** @var OrderExtension $orderExtension */
        $orderExtension = $result->getExtensionAttributes()
            ?: $this->orderExtensionFactory->create();

        $orderExtension->setUnified($this->unifiedSaleFactory->create(['entity' => $result]));

        $result->setExtensionAttributes($orderExtension);

        return $result;
    }
}
