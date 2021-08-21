<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action\Hydration;

use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory as CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Api\Data\OrderItemExtensionInterfaceFactory as OrderItemExtensionFactory;
use Magento\Sales\Model\Order\Item as OrderItem;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\OrderItem\CollectionFactory as PrintSpecItemCollectionFactory;

class AddPrintSpecsToOrderItems
{
    /** @var PrintSpecItemCollectionFactory */
    private $printSpecItemCollectionFactory;

    /** @var OrderItemExtensionFactory */
    private $orderItemExtensionFactory;

    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory,
        PrintSpecItemCollectionFactory $printSpecQuoteItemCollectionFactory
    ) {
        $this->printSpecItemCollectionFactory = $printSpecQuoteItemCollectionFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    /**
     * @param array<int, OrderItem>|null $items
     * @return array
     */
    public function execute(?iterable $items): array
    {
        if (!$items) {
            return [];
        }

        foreach ($items as $item) {
            $attributes = $item->getExtensionAttributes() ?: $this->orderItemExtensionFactory->create();

            if ($attributes
                && $attributes->getPrintSpecItem()
                && $attributes->getPrintSpecItem()->getPrintSpecId()) {
                continue;
            }

            $printSpecOrderItem = $this->printSpecItemCollectionFactory->create()
                ->addFieldToFilter('order_item_id', $item->getItemId())
                ->filterDeletedPrintSpecs()
                ->getFirstItem();

            if (!$printSpecOrderItem->getId()) {
                continue;
            }

            $attributes->setPrintSpecItems($printSpecOrderItem);
            $item->setExtensionAttributes($attributes);
        }

        return $items;
    }
}
