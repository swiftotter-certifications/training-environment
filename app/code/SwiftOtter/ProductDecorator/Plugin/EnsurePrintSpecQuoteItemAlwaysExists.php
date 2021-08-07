<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as PrintSpecQuoteItemFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemExtension;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as CartItem;

class EnsurePrintSpecQuoteItemAlwaysExists
{
    /** @var CartItemExtensionFactory */
    private $cartItemExtensionFactory;

    /** @var PrintSpecQuoteItemFactory */
    private $printSpecQuoteItemFactory;

    public function __construct(
        CartItemExtensionFactory $cartItemExtensionFactory,
        PrintSpecQuoteItemFactory $printSpecQuoteItemFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->printSpecQuoteItemFactory = $printSpecQuoteItemFactory;
    }

    public function afterCreate($subject, $result)
    {
        if (!($result instanceof CartItem)) {
            return $result;
        }

        /** @var CartItemExtension $cartItemExtension */
        $cartItemExtension = $result->getExtensionAttributes()
            ?: $this->cartItemExtensionFactory->create();

        $cartItemExtension->setPrintSpecQuoteItem($this->printSpecQuoteItemFactory->create());

        $result->setExtensionAttributes($cartItemExtension);

        return $result;
    }
}
