<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Collector;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

class ItemData implements DataCollectorInterface
{
    /**
     * @var array
     */
    private $allowedTypes;

    public function __construct(array $allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    public function collect(OrderInterface $order, HeaderData $headerData): array
    {
        $items = [];

        foreach ($order->getItems() as $item) {
            if (!in_array($item->getProductType(), $this->allowedTypes)) {
                continue;
            }

            $items[] = $this->transform($item);
        }

        return [
            'items' => $items
        ];
    }

    private function transform(OrderItemInterface $orderItem)
    {
        return [
            'sku' => $orderItem->getSku(),
            'qty' => $orderItem->getQtyOrdered(),
            'item_price' => $orderItem->getBasePrice(),
            'item_cost' => $orderItem->getBaseCost(),
            'total' => $orderItem->getRowTotal()
        ];
    }
}