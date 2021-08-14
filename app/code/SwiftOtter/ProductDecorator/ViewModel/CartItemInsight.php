<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Action\FindQuoteItemsChildItems;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\Source\Colors;

class CartItemInsight implements ArgumentInterface
{
    /** @var PrintSpecToPriceRequest */
    private $printSpecToPriceRequest;

    /** @var FindQuoteItemsChildItems */
    private $findQuoteItemsChildItems;

    /** @var LocationResource */
    private $locationResource;

    /** @var Colors */
    private $colors;

    /** @var PrintMethodResource */
    private $printMethodResource;

    public function __construct(
        PrintSpecToPriceRequest $printSpecToPriceRequest,
        FindQuoteItemsChildItems $findQuoteItemsChildItems,
        LocationResource $locationResource,
        Colors $colors,
        PrintMethodResource $printMethodResource
    ) {
        $this->printSpecToPriceRequest = $printSpecToPriceRequest;
        $this->findQuoteItemsChildItems = $findQuoteItemsChildItems;
        $this->locationResource = $locationResource;
        $this->colors = $colors;
        $this->printMethodResource = $printMethodResource;
    }

    public function isDecorated(QuoteItem $quoteItem): bool
    {
        return $quoteItem->getExtensionAttributes()
            && $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()
            && $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()->getPrintSpecId();
    }

    /**
     * @param QuoteItem $quoteItem
     * @return array<int, string>
     */
    public function getDetails(QuoteItem $quoteItem): array
    {
        if (!$this->isDecorated($quoteItem)) {
            return [];
        }

        $printSpec = $this->printSpecToPriceRequest->execute(
            $quoteItem->getExtensionAttributes()->getPrintSpecQuoteItem()->getPrintSpecId(),
            array_merge([$quoteItem], $this->findQuoteItemsChildItems->execute($quoteItem->getQuote(), $quoteItem))
        );

        $output = [];
        $output[] = $this->getLocations($printSpec);

        return $output;
    }

    private function mapColors(array $input): array
    {
        $colors = $this->colors->toOptionArray();

        $output = array_map(function($inputColor) use ($colors) {
            $choice = array_filter($colors, function($colorOption) use ($inputColor) {
                return $colorOption['value'] === $inputColor;
            });

            if (!count($choice)) {
                return null;
            }

            $choice = reset($choice);
            return $choice ? $choice['label'] : null;
        }, $input);

        return array_filter($output);
    }

    /**
     * @param PriceRequestInterface $printSpec
     * @return array
     */
    private function getLocations(PriceRequestInterface $printSpec): array
    {
        $locations = [
            'title' => __('Location'),
            'values' => []
        ];

        foreach ($printSpec->getLocations() as $location) {
            $locations['values'][] = [
                'title' => $this->locationResource->getNameFor($location->getLocationId()),
                'colors' => implode(', ', $this->mapColors($location->getColors())),
                'print_method' => $this->printMethodResource->getPrintMethodNameFor($location->getPrintMethodId())
            ];
        }
        return $locations;
    }
}
