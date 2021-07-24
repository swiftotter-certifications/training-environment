<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Sales\Api\Data\OrderInterface as Order;
use Magento\Sales\Model\Service\OrderService;
use SwiftOtter\DownloadProduct\Action\TriggerOrderItemWebhook as Action;

class TriggerOrderWebhook
{
    /** @var Action */
    private $triggerOrderWebhook;

    public function __construct(Action $triggerOrderWebhook)
    {
        $this->triggerOrderWebhook = $triggerOrderWebhook;
    }

    public function afterPlace(OrderService $subject, Order $order)
    {
        foreach ($order->getItems() as $item) {
            $this->triggerOrderWebhook->execute($order, $item);
        }

        return $order;
    }
}
