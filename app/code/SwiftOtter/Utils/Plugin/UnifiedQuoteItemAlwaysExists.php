<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use SwiftOtter\Utils\Model\UnifiedSale\ItemFactory as UnifiedSaleItemFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemExtensionInterface as CartItemExtension;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory as CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as CartItem;

class UnifiedQuoteItemAlwaysExists
{
    /** @var CartItemExtensionFactory */
    private $cartItemExtensionFactory;

    /** @var UnifiedSaleItemFactory */
    private $unifiedSaleItemFactory;

    public function __construct(
        CartItemExtensionFactory $cartItemExtensionFactory,
        UnifiedSaleItemFactory $unifiedSaleItemFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->unifiedSaleItemFactory = $unifiedSaleItemFactory;
    }

    public function afterCreate($subject, $result)
    {
        if (!($result instanceof CartItem)) {
            return $result;
        }

        /** @var CartItemExtension $cartItemExtension */
        $cartItemExtension = $result->getExtensionAttributes()
            ?: $this->cartItemExtensionFactory->create();

        $cartItemExtension->setUnified($this->unifiedSaleItemFactory->create(['item' => $result]));

        $result->setExtensionAttributes($cartItemExtension);

        return $result;
    }
}
