<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/12/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Defaults;

use Magento\Framework\DataObject as Target;
use Magento\Quote\Api\Data\CartItemExtension;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Api\Data\OrderItemExtensionInterfaceFactory as OrderItemExtensionFactory;
use Magento\Sales\Model\Order\Item as OrderItem;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceExtensionFactory as ExtensionFactory;
use Magento\Framework\Data\Collection\EntityFactory as TargetFactory;
use SwiftOtter\ProductDecorator\Model\PrintSpec\OrderItemFactory as PrintSpecOrderItemFactory;

class EnsureOrderItemHasPrintSpecAssociation
{
    /** @var OrderItemExtensionFactory */
    private $orderItemExtensionFactory;

    /** @var PrintSpecOrderItemFactory */
    private $printSpecOrderItemFactory;

    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory,
        PrintSpecOrderItemFactory $printSpecOrderItemFactory
    ) {
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->printSpecOrderItemFactory = $printSpecOrderItemFactory;
    }

    /**
     * @param $targetFactory
     * @param OrderItem $target
     * @return OrderItem
     */
    public function afterCreate($targetFactory, $target)
    {
        if (!($target instanceof OrderItem)) {
            return $target;
        }

        $orderItemExtension = $target->getExtensionAttributes()
            ?: $this->orderItemExtensionFactory->create();

        $orderItemExtension->setPrintSpecItem($this->printSpecOrderItemFactory->create());

        $target->setExtensionAttributes($orderItemExtension);

        return $target;
    }
}
