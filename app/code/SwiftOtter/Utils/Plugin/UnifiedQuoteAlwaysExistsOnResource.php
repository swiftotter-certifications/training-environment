<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Plugin;

use Magento\Quote\Model\ResourceModel\Quote as QuoteResource;
use SwiftOtter\Utils\Model\UnifiedSaleFactory as UnifiedSaleFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartExtensionInterface as QuoteExtension;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory as QuoteExtensionFactory;
use Magento\Quote\Model\Quote as Quote;

class UnifiedQuoteAlwaysExistsOnResource
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

    public function afterLoadByIdWithoutStore(QuoteResource $subject, QuoteResource $result, Quote $quote): QuoteResource
    {
        $this->add($quote);
        return $result;
    }

    public function afterLoadByCustomerId(QuoteResource $subject, QuoteResource $result, Quote $quote)
    {
        $this->add($quote);
        return $result;
    }

    public function afterLoadActive(QuoteResource $subject, QuoteResource $result, Quote $quote)
    {
        $this->add($quote);
        return $result;
    }

    private function add(Quote $quote)
    {
        /** @var QuoteExtension $quoteExtension */
        $quoteExtension = $quote->getExtensionAttributes()
            ?: $this->quoteExtensionFactory->create();

        $quoteExtension->setUnified($this->unifiedSaleFactory->create(['entity' => $quote]));

        $quote->setExtensionAttributes($quoteExtension);
    }
}
