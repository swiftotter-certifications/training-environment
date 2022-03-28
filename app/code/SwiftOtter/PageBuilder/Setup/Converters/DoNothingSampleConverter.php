<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/26/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\PageBuilder\Setup\Converters;

use Magento\Framework\DB\DataConverter\DataConversionException;
use Magento\Framework\DB\DataConverter\DataConverterInterface;
use Magento\PageBuilder\Model\Dom\HtmlDocumentFactory;

class DoNothingSampleConverter implements DataConverterInterface
{
    private HtmlDocumentFactory $htmlDocumentFactory;

    public function __construct(HtmlDocumentFactory $htmlDocumentFactory)
    {
        $this->htmlDocumentFactory = $htmlDocumentFactory;
    }

    /**
     * See this: \Magento\PageBuilder\Model\Dom\HtmlDocument
     *
     * @param $value
     * @return string
     */
    public function convert($value)
    {
        $document = $this->htmlDocumentFactory->create(['document' => $value]);
        // do something here

        /**
         * NOTE: if styles are present, these are cleaned out with either
         * $document->stripHtmlWrapperTags() or (string)$document.
         */
        return $value;
    }
}
