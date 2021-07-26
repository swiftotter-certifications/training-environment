<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\Option as QuoteItemOption;
use Magento\Quote\Model\Quote\Item\ToOrderItem as ToOrderItemConverter;
use Magento\Quote\Model\ResourceModel\Quote\Item\Option\CollectionFactory as QuoteItemOptionCollectionFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use SwiftOtter\GiftCard\Constants;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class MoveQuoteItemOptionsToOrderItem
{
    /** @var QuoteItemOptionCollectionFactory */
    private $collectionFactory;

    public function __construct(QuoteItemOptionCollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function afterConvert(
        ToOrderItemConverter $subject,
        OrderItemInterface $orderItem,
        CartItemInterface $cartItem
    ) {
        if ($orderItem->getProductType() !== GiftCard::TYPE_CODE) {
            return $orderItem;
        }

        $orderItemOptions = $orderItem->getProductOptions();

        $quoteItemOptions = $this->collectionFactory->create()
            ->getOptionsByItem($cartItem);

        /** @var QuoteItemOption $option */
        foreach ($quoteItemOptions as $option) {
            if (in_array($option->getData('code'), Constants::OPTION_LIST)) {
                $orderItemOptions[$option->getData('code')] = $option->getData('value');
            }
        }

        $orderItem->setProductOptions($orderItemOptions);

        return $orderItem;
    }
}