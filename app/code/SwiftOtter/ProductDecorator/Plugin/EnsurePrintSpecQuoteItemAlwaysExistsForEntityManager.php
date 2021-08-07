<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace Catalog\Cart\Plugin;

use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItem as PrintSpecQuoteItemFactory;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemExtension;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\ItemFactory as QuoteItemFactory;

class EnsurePrintSpecQuoteItemAlwaysExistsForEntityManager
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

    public function afterCreate(EntityFactory $subject, DataObject $result): DataObject
    {
        if (!($result instanceof QuoteItem)) {
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
