<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Observer;

use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem as PrintSpecQuoteItemResource;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Api\Data\CartItemInterface;

class ApplyPrintSpecForQuoteItem implements ObserverInterface
{
    private PrintSpecQuoteItemResource $printSpecQuoteItemResource;

    public function __construct(
        PrintSpecQuoteItemResource $printSpecQuoteItemResource
    ) {
        $this->printSpecQuoteItemResource = $printSpecQuoteItemResource;
    }

    public function execute(Observer $observer)
    {
        /** @var CartItemInterface $quoteItem */
        $quoteItem = $observer->getData('item');
        if (!$quoteItem->getExtensionAttributes()
            || !$quoteItem->getExtensionAttributes()->getPrintSpecItem()
            || !$quoteItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId()) {
            return;
        }

        $this->printSpecQuoteItemResource->replace(
            $quoteItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId(),
            (int)$quoteItem->getItemId()
        );

        $quoteItem->getExtensionAttributes()->getPrintSpecItem()->setItemId((int)$quoteItem->getItemId());
    }
}
