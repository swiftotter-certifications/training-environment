<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Plugin;

use Magento\Catalog\Model\Product\Type;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Model\Quote as CartModel;
use SwiftOtter\HandlingFee\Action\AssembleFees;
use SwiftOtter\HandlingFee\Action\RecalculateHandlingFeesForCart;
use SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterfaceFactory;
use SwiftOtter\HandlingFee\Model\HandlingFee;
use SwiftOtter\HandlingFee\Model\HandlingFeeFactory;
use SwiftOtter\HandlingFee\Model\ProductResolver;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFee as PalletResource;

class RefigureQuotesAfterAddProduct
{
    /** @var RecalculateHandlingFeesForCart */
    private $recalculateHandlingFeesForCart;

    public function __construct(
        RecalculateHandlingFeesForCart $recalculateHandlingFeesForCart
    ) {
        $this->recalculateHandlingFeesForCart = $recalculateHandlingFeesForCart;
    }

    public function afterAddProduct(CartModel $cart, $itemAdded)
    {
        if (!$cart->getId()) {
            return null;
        }

        $this->recalculateHandlingFeesForCart->execute($cart);

        return $itemAdded;
    }
}
