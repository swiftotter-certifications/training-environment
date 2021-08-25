<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use SwiftOtter\Utils\Model\UnifiedSaleFactory as UnifiedSaleFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartExtensionInterface as QuoteExtension;
use Magento\Sales\Api\Data\OrderExtensionInterfaceFactory as QuoteExtensionFactory;
use Magento\Quote\Model\Quote as Quote;

class UnifiedQuoteAlwaysExists
{
    /** @var QuoteExtensionFactory */
    private $quoteExtensionFactory;

    /** @var UnifiedSaleFactory */
    private $unifiedSaleFactory;

    public function __construct(
        QuoteExtensionFactory $quoteExtensionFactory,
        UnifiedSaleFactory $unifiedSaleFactory
    ) {
        $this->quoteExtensionFactory = $quoteExtensionFactory;
        $this->unifiedSaleFactory = $unifiedSaleFactory;
    }

    public function afterCreate($subject, $result)
    {
        if (!($result instanceof Quote)) {
            return $result;
        }

        /** @var QuoteExtension $quoteExtension */
        $quoteExtension = $result->getExtensionAttributes()
            ?: $this->quoteExtensionFactory->create();

        $quoteExtension->setUnified($this->unifiedSaleFactory->create(['entity' => $result]));

        $result->setExtensionAttributes($quoteExtension);

        return $result;
    }
}
