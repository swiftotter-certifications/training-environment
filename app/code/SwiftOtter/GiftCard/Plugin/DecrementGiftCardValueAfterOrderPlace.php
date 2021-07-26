<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/20/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface as Order;
use Magento\Sales\Model\Service\OrderService;
use SwiftOtter\GiftCard\Model\GiftCardUsage;
use SwiftOtter\GiftCard\Model\GiftCardUsageFactory;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\Repository\GiftCardUsageRepository;

class DecrementGiftCardValueAfterOrderPlace
{
    /** @var GiftCardUsageFactory */
    private $giftCardUsageFactory;

    /** @var GiftCardUsageRepository */
    private $giftCardUsageRepository;

    /** @var CartRepositoryInterface */
    private $cartRepository;
    /** @var GiftCardRepository */
    private $giftCardRepository;

    public function __construct(
        GiftCardUsageFactory $giftCardUsageFactory,
        GiftCardUsageRepository $giftCardUsageRepository,
        GiftCardRepository $giftCardRepository,
        CartRepositoryInterface $cartRepository
    ) {
        $this->giftCardUsageFactory = $giftCardUsageFactory;
        $this->giftCardUsageRepository = $giftCardUsageRepository;
        $this->cartRepository = $cartRepository;
        $this->giftCardRepository = $giftCardRepository;
    }

    public function afterPlace(OrderService $subject, Order $order)
    {
        $quote = $this->cartRepository->get($order->getQuoteId());

        if (!$quote->getExtensionAttributes()->getGiftCardId()) {
            return $order;
        }

        $giftCardId = $quote->getExtensionAttributes()->getGiftCardId();
        $giftCard = $this->giftCardRepository->getById($giftCardId);

        /** @var GiftCardUsage $usage */
        $usage = $this->giftCardUsageFactory->create();
        $usage->setOrderId((int)$order->getId());
        $usage->setGiftCardId((int)$giftCardId);
        $usage->setValueChange((float)$quote->getData('gift_card_amount'));

        $this->giftCardUsageRepository->save($usage);

        $giftCard->setCurrentValue($giftCard->getCurrentValue() - abs($usage->getValueChange()));
        $this->giftCardRepository->save($giftCard);

        return $order;
    }
}