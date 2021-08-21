<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action\Hydration;

use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory as CartItemExtensionFactory;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem\CollectionFactory as PrintSpecItemCollectionFactory;

class AddPrintSpecsToQuoteItems
{
    /** @var CartItemExtensionFactory */
    private $cartItemExtensionFactory;

    /** @var PrintSpecItemCollectionFactory */
    private $printSpecItemCollectionFactory;

    public function __construct(
        CartItemExtensionFactory $cartItemExtensionFactory,
        PrintSpecItemCollectionFactory $printSpecQuoteItemCollectionFactory
    ) {
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->printSpecItemCollectionFactory = $printSpecQuoteItemCollectionFactory;
    }

    /**
     * @param array<int, QuoteItem>|null $items
     * @return array
     */
    public function execute(?iterable $items): iterable
    {
        if (!$items) {
            return [];
        }

        foreach ($items as $item) {
            $attributes = $item->getExtensionAttributes() ?: $this->cartItemExtensionFactory->create();

            if ($attributes
                && $attributes->getPrintSpecItem()
                && $attributes->getPrintSpecItem()->getPrintSpecId()) {
                continue;
            }

            $printSpecQuoteItem = $this->printSpecItemCollectionFactory->create()
                ->addFieldToFilter('quote_item_id', $item->getItemId())
                ->filterDeletedPrintSpecs()
                ->getFirstItem();

            if (!$printSpecQuoteItem->getId()) {
                continue;
            }

            $attributes->setPrintSpecItem($printSpecQuoteItem);
            $item->setExtensionAttributes($attributes);
        }

        return $items;
    }
}
