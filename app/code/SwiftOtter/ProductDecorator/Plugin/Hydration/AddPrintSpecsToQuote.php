<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 01/09/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Hydration;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\ResourceModel\Quote\Collection;
use SwiftOtter\ProductDecorator\Action\Hydration\AddPrintSpecsToQuoteItems as Action;

class AddPrintSpecsToQuote
{
    /** @var Action */
    private $addPrintSpecsToQuoteItems;

    public function __construct(
        Action $addPrintSpecsToQuoteItems
    ) {
        $this->addPrintSpecsToQuoteItems = $addPrintSpecsToQuoteItems;
    }

    public function afterGet(CartRepositoryInterface $subject, CartInterface $resultCart): CartInterface
    {
        $this->getCartItemArtId($resultCart);

        return $resultCart;
    }

    public function afterGetList(CartRepositoryInterface $subject, $result)
    {
        /** @var CartInterface $cart */
        foreach ($result->getItems() as $cart) {
            $this->afterGet($subject, $cart);
        }
        return $result;
    }

    private function getCartItemArtId(CartInterface $cart): CartInterface
    {
        $cartItems = array_merge($cart->getAllItems());
        if ($cartItems === null) {
            return $cart;
        }

        $this->addPrintSpecsToQuoteItems->execute($cartItems);

        return $cart;
    }
}
