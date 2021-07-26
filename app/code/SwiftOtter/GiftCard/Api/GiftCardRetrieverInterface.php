<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/9/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Api;

use Magento\Checkout\Api\Data\PaymentDetailsInterface;

interface GiftCardRetrieverInterface
{
    /**
     * @param string $cartId
     * @param string $giftCardCode
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function applyGuest(string $cartId, string $giftCardCode): PaymentDetailsInterface;

    /**
     * @param int $cartId
     * @param string $giftCardCode
     * @return \Magento\Checkout\Api\Data\PaymentDetailsInterface
     */
    public function applyCustomer(int $cartId, string $giftCardCode): PaymentDetailsInterface;
}