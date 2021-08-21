<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 01/09/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Hydration;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use SwiftOtter\ProductDecorator\Action\Hydration\AddPrintSpecsToOrderItems as Action;

class AddPrintSpecsToOrder
{
    /** @var Action */
    private $addPrintSpecsToOrderItems;

    public function __construct(
        Action $addPrintSpecsToOrderItems
    ) {
        $this->addPrintSpecsToOrderItems = $addPrintSpecsToOrderItems;
    }

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $resultOrder): OrderInterface
    {
        $this->addPrintSpecs($resultOrder);

        return $resultOrder;
    }

    public function afterGetList(OrderRepositoryInterface $subject, $result)
    {
        /** @var OrderInterface $order */
        foreach ($result->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $result;
    }

    private function addPrintSpecs(OrderInterface $order): OrderInterface
    {
        $cartItems = $order->getAllItems();
        if ($cartItems === null) {
            return $order;
        }

        $this->addPrintSpecsToOrderItems->execute($cartItems);

        return $order;
    }
}
