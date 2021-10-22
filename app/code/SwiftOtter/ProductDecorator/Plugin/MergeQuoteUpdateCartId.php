<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/16/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Quote\Model\Quote;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResource;

class MergeQuoteUpdateCartId
{
    private PrintSpecResource $printSpecResource;

    public function __construct(PrintSpecResource $printSpecResource)
    {
        $this->printSpecResource = $printSpecResource;
    }

    public function afterMerge(Quote $primary, Quote $output, Quote $toBeMerged): Quote
    {
        /** @var Quote\Item $item */
        foreach ($primary->getAllItems() as $item) {
            if (!$item->getExtensionAttributes()
                || !$item->getExtensionAttributes()->getPrintSpecItem()
                || !$item->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId()) {
                continue;
            }

            $this->printSpecResource->updateCartId(
                $item->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId(),
                (int)$primary->getId()
            );
        }

        return $output;
    }
}
