<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Action\FindItemsChildItems;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest;
use SwiftOtter\ProductDecorator\Action\PrintSpecToArray;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\Source\Colors;

class CartItemInsight implements ArgumentInterface
{
    /** @var PrintSpecToArray */
    private $printSpecToArray;

    public function __construct(PrintSpecToArray $printSpecToArray)
    {
        $this->printSpecToArray = $printSpecToArray;
    }

    public function isDecorated(QuoteItem $quoteItem): bool
    {
        return $quoteItem->getExtensionAttributes()
            && $quoteItem->getExtensionAttributes()->getPrintSpecItem()
            && $quoteItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId();
    }

    /**
     * @param QuoteItem $quoteItem
     * @return array<int, string>
     */
    public function getDetails(QuoteItem $quoteItem): array
    {
        return $this->printSpecToArray->execute($quoteItem->getExtensionAttributes()->getUnified());
    }
}
