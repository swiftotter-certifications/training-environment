<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use SwiftOtter\Utils\Model\UnifiedSale\ItemFactory as UnifiedSaleItemFactory;
use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderItemExtensionInterface as OrderItemExtension;
use Magento\Sales\Api\Data\OrderItemExtensionInterfaceFactory as OrderItemExtensionFactory;
use Magento\Order\Model\Order\Item as OrderItem;

class UnifiedOrderItemAlwaysExists
{
    /** @var OrderItemExtensionFactory */
    private $orderItemExtensionFactory;

    /** @var UnifiedSaleItemFactory */
    private $unifiedSaleItemFactory;

    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory,
        UnifiedSaleItemFactory $unifiedSaleItemFactory
    ) {
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->unifiedSaleItemFactory = $unifiedSaleItemFactory;
    }

    public function afterCreate($subject, $result)
    {
        if (!($result instanceof OrderItem)) {
            return $result;
        }

        /** @var OrderItemExtension $orderItemExtension */
        $orderItemExtension = $result->getExtensionAttributes()
            ?: $this->orderItemExtensionFactory->create();

        $orderItemExtension->setUnified($this->unifiedSaleItemFactory->create(['item' => $result]));

        $result->setExtensionAttributes($orderItemExtension);

        return $result;
    }
}
