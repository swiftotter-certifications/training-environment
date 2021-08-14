<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\OrderItem as PrintSpecOrderItemResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem as PrintSpecQuoteItemResource;

class ConvertQuoteToOrderForPrintSpec implements ObserverInterface
{
    /** @var PrintSpecOrderItemResource */
    private $printSpecOrderItemResource;

    /** @var PrintSpecQuoteItemResource */
    private $printSpecQuoteItemResource;

    public function __construct(
        PrintSpecOrderItemResource $printSpecOrderItemResource,
        PrintSpecQuoteItemResource $printSpecQuoteItemResource
    ) {
        $this->printSpecOrderItemResource = $printSpecOrderItemResource;
        $this->printSpecQuoteItemResource = $printSpecQuoteItemResource;
    }

    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        foreach ($order->getAllItems() as $item) {
            $printSpecId = $this->printSpecQuoteItemResource->getByQuoteItem((int)$item->getQuoteItemId());
            $this->printSpecOrderItemResource->replace($printSpecId, (int)$item->getItemId());
        }
    }
}
