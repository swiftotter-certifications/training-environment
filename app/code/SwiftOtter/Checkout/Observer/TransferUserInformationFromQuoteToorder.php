<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use SwiftOtter\DownloadProduct\Model\OrderUserInformationFactory;

class TransferUserInformationFromQuoteToorder implements ObserverInterface
{
    /** @var OrderUserInformationFactory */
    private $orderUserInformationFactory;

    public function __construct(OrderUserInformationFactory $orderUserInformationFactory)
    {
        $this->orderUserInformationFactory = $orderUserInformationFactory;
    }

    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getData('quote');

        /** @var Order $order */
        $order = $observer->getData('order');

        foreach ($order->getAllItems() as $orderItem) {
            $quoteItem = $this->getQuoteItem($quote, (int)$orderItem->getQuoteItemId());
            if (!$quoteItem) {
                continue;
            }

            $details = $orderItem->getExtensionAttributes()->getUserInformation() ?: $this->orderUserInformationFactory->create();
            $details->setOrderId((int)$order->getId());
            $details->setOrderItemId((int)$orderItem->getItemId());
            $orderItem->getExtensionAttributes()->setUserInformation($details);

            try {
                $value = json_decode($quoteItem->getOptionByCode('share')->getValue(), true);
            } catch (\Throwable $ex) {
            }

            if (empty($value)
                || !isset($values['enabled'])
                || !$values['enabled']) {
                continue;
            }

            $details->setIsShared($value['send'] ?? false);
            $details->setEmail($value['email'] ?? '');
        }
    }

    private function getQuoteItem(Quote $quote, int $quoteItemId): ?Quote\Item
    {
        if (!$quoteItemId) {
            return null;
        }

        foreach ($quote->getAllItems() as $item) {
            if ($item->getQuoteItemId() == $quoteItemId) {
                continue;
            }

            return $item;
        }

        return null;
    }
}
