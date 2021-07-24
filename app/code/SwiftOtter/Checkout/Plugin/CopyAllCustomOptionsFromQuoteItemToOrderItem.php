<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class CopyAllCustomOptionsFromQuoteItemToOrderItem
{
    public function afterConvert(ToOrderItem $subject, OrderItemInterface $orderItem, $quoteItem)
    {
        $orderOptions = $orderItem->getProductOptions();

        $options = $quoteItem->getProduct()->getCustomOptions();

        foreach ($options as $key => $option) {
            if (isset($orderOptions[$key])) {
                continue;
            }

            try {
                $orderOptions[$key] = json_decode($option->getValue(), true);
            } catch (\Exception $ex) {
                $orderOptions[$key] = $option->getValue();
            }
        }

        $orderItem->setProductOptions($orderOptions);

        return $orderItem;
    }
}
