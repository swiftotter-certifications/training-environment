<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/12/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Defaults;

use Magento\Framework\DataObject as Target;
use Magento\Quote\Api\Data\CartItemExtension;
use Magento\Quote\Api\Data\CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterfaceExtensionFactory as ExtensionFactory;
use Magento\Framework\Data\Collection\EntityFactory as TargetFactory;
use SwiftOtter\ProductDecorator\Model\PrintSpec\QuoteItemFactory as PrintSpecQuoteItemFactory;

class EnsureQuoteItemHasPrintSpecAssociation
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

    /**
     * @param $targetFactory
     * @param QuoteItem $target
     * @return QuoteItem
     */
    public function afterCreate($targetFactory, $target)
    {
        if (!($target instanceof QuoteItem)) {
            return $target;
        }

        /** @var CartItemExtension $cartItemExtension */
        $cartItemExtension = $target->getExtensionAttributes()
            ?: $this->cartItemExtensionFactory->create();

        $cartItemExtension->setPrintSpecItem($this->printSpecQuoteItemFactory->create());

        $target->setExtensionAttributes($cartItemExtension);

        return $target;
    }
}
