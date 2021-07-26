<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/9/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Endpoint;

use Magento\Checkout\Model\PaymentDetailsFactory;
use Magento\Framework\Exception\StateException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\PaymentMethodManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use SwiftOtter\GiftCard\Api\GiftCardRetrieverInterface;
use SwiftOtter\GiftCard\Model\GiftCard;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardQuote;
use Magento\Checkout\Api\Data\PaymentDetailsInterface;

class GiftCardRetriever implements GiftCardRetrieverInterface
{
    /** @var QuoteIdMaskFactory */
    private $quoteIdMaskFactory;

    /** @var CartRepositoryInterface */
    private $cartRepository;

    /** @var GiftCardRepository */
    private $giftCardRepository;

    /** @var PaymentDetailsFactory */
    private $paymentDetailsFactory;

    /** @var PaymentMethodManagementInterface */
    private $paymentMethodManagement;

    /** @var CartTotalRepositoryInterface */
    private $cartTotalsRepository;

    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CartRepositoryInterface $cartRepository,
        GiftCardRepository $giftCardRepository,
        PaymentDetailsFactory $paymentDetailsFactory,
        PaymentMethodManagementInterface $paymentMethodManagement,
        CartTotalRepositoryInterface $cartTotalsRepository
    ){
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->cartRepository = $cartRepository;
        $this->giftCardRepository = $giftCardRepository;
        $this->paymentDetailsFactory = $paymentDetailsFactory;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->cartTotalsRepository = $cartTotalsRepository;
    }

    public function applyGuest(string $cartId, string $giftCardCode): PaymentDetailsInterface
    {
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');

        return $this->applyCustomer((int)$quoteIdMask->getQuoteId(), $giftCardCode);
    }

    public function applyCustomer(int $cartId, string $giftCardCode): PaymentDetailsInterface
    {
        $giftCard = $this->giftCardRepository->getByCode($giftCardCode);

        if ($giftCard->getStatus() === GiftCard::STATUS_USED
            || $giftCard->getCurrentValue() <= 0) {
            throw new StateException(__('This gift card has been used or has no value.'));
        }

        $cart = $this->cartRepository->getActive($cartId);
        $cart->getExtensionAttributes()->setGiftCardId($giftCard->getId());

        $this->cartRepository->save($cart);

        /** @var \Magento\Checkout\Api\Data\PaymentDetailsInterface $paymentDetails */
        $paymentDetails = $this->paymentDetailsFactory->create();
        $paymentDetails->setPaymentMethods($this->paymentMethodManagement->getList($cartId));
        $paymentDetails->setTotals($this->cartTotalsRepository->get($cartId));
        return $paymentDetails;
    }
}