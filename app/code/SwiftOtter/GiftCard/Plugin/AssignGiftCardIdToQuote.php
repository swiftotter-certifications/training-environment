<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/9/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory as CartExtensionFactory;
use Magento\Quote\Api\Data\CartInterface;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardQuote;

class AssignGiftCardIdToQuote
{
    /** @var CartExtensionFactory */
    private $cartExtensionFactory;

    /** @var GiftCardQuote */
    private $giftCardQuote;

    public function __construct(CartExtensionFactory $cartExtensionFactory, GiftCardQuote $giftCardQuote)
    {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->giftCardQuote = $giftCardQuote;
    }

    public function afterGet(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetForCustomer(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetActiveForCustomer(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetActive(CartRepositoryInterface $subject, CartInterface $cart)
    {
        $this->loadExtensionAttributes($cart);
        return $cart;
    }

    public function afterGetList(CartRepositoryInterface $subject, $results)
    {
        /** @var \Magento\Quote\Api\Data\CartSearchResultsInterface $results */
        foreach ($results->getItems() as $cart) {
            $this->loadExtensionAttributes($cart);
        }

        return $results;
    }

    public function afterSave(CartRepositoryInterface $subject, $result, CartInterface $cart)
    {
        if ($cart->getExtensionAttributes()->getGiftCardId() && $cart->getId()) {
            $this->giftCardQuote->add((int)$cart->getId(), $cart->getExtensionAttributes()->getGiftCardId());
        }

        return $cart;
    }

    private function loadExtensionAttributes(CartInterface $cart): void
    {
        $extensionAttributes = $cart->getExtensionAttributes() ?: $this->cartExtensionFactory->create();

        if ($extensionAttributes->getGiftCardId()) {
            return;
        }

        $giftCardId = $this->giftCardQuote->get((int)$cart->getId());
        $extensionAttributes->setGiftCardId($giftCardId);

        $cart->setExtensionAttributes($extensionAttributes);
    }
}