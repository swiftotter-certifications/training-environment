<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/18/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Hydration;

use Magento\Quote\Api\CartItemRepositoryInterface;
use SwiftOtter\ProductDecorator\Action\Hydration\AddPrintSpecsToQuoteItems as Action;

class AddPrintSpecsToQuoteItems
{
    /** @var Action */
    private $addPrintSpecsToQuoteItems;

    public function __construct(Action $addPrintSpecsToQuoteItems)
    {
        $this->addPrintSpecsToQuoteItems = $addPrintSpecsToQuoteItems;
    }
    public function afterGetList(CartItemRepositoryInterface $context, $items)
    {
        return $this->addPrintSpecsToQuoteItems->execute($items);
    }
}
