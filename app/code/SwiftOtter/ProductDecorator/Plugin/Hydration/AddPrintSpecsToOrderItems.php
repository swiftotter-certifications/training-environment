<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/18/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Hydration;

use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use SwiftOtter\ProductDecorator\Action\Hydration\AddPrintSpecsToOrderItems as Action;

class AddPrintSpecsToOrderItems
{
    /** @var Action */
    private $addPrintSpecsToOrderItems;

    public function __construct(Action $addPrintSpecsToOrderItems)
    {
        $this->addPrintSpecsToOrderItems = $addPrintSpecsToOrderItems;
    }
    public function afterGetList(OrderItemRepositoryInterface $context, $items)
    {
        return $this->addPrintSpecsToOrderItems->execute($items);
    }
}
