<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action\Hydration;

use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory as CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem\CollectionFactory as PrintSpecQuoteItemCollectionFactory;

class AddPrintSpecsToQuoteItems
{
    /** @var CartItemExtensionFactory */
    private $cartItemExtensionFactory;

    /** @var PrintSpecQuoteItemCollectionFactory */
    private $printSpecQuoteItemCollectionFactory;

    public function __construct(
        CartItemExtensionFactory $cartItemExtensionFactory,
        PrintSpecQuoteItemCollectionFactory $printSpecQuoteItemCollectionFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->printSpecQuoteItemCollectionFactory = $printSpecQuoteItemCollectionFactory;
    }

    /**
     * @param array<int, QuoteItem>|null $items
     * @return array
     */
    public function execute(?iterable $items): array
    {
        if (!$items) {
            return [];
        }

        foreach ($items as $item) {
            $attributes = $item->getExtensionAttributes() ?: $this->cartItemExtensionFactory->create();

            if ($attributes
                && $attributes->getPrintSpecQuoteItem()
                && $attributes->getPrintSpecQuoteItem()->getPrintSpecId()) {
                continue;
            }

            $printSpecQuoteItem = $this->printSpecQuoteItemCollectionFactory->create()
                ->addFieldToFilter('quote_item_id', $item->getItemId())
                ->filterDeletedPrintSpecs()
                ->getFirstItem();

            if (!$printSpecQuoteItem->getId()) {
                continue;
            }

            $attributes->setPrintSpecQuoteItem($printSpecQuoteItem);
            $item->setExtensionAttributes($attributes);
        }

        return $items;
    }
}
