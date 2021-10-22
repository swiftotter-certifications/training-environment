<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\Source\Colors;
use Swiftotter\Utils\Model\UnifiedSale\Item as UnifiedSaleItem;

class PrintSpecToArray
{
    /** @var PrintSpecToPriceRequest */
    private $printSpecToPriceRequest;

    /** @var FindItemsChildItems */
    private $findQuoteItemsChildItems;

    /** @var LocationResource */
    private $locationResource;

    /** @var Colors */
    private $colors;

    /** @var PrintMethodResource */
    private $printMethodResource;

    public function __construct(
        PrintSpecToPriceRequest $printSpecToPriceRequest,
        FindItemsChildItems     $findQuoteItemsChildItems,
        LocationResource        $locationResource,
        Colors                  $colors,
        PrintMethodResource     $printMethodResource
    ) {
        $this->printSpecToPriceRequest = $printSpecToPriceRequest;
        $this->findQuoteItemsChildItems = $findQuoteItemsChildItems;
        $this->locationResource = $locationResource;
        $this->colors = $colors;
        $this->printMethodResource = $printMethodResource;
    }

    /**
     * @param UnifiedSaleItem $unifiedSaleItem
     * @return array<int, string>
     */
    public function execute(UnifiedSaleItem $unifiedSaleItem): array
    {
        if (!$this->isDecorated($unifiedSaleItem)) {
            return [];
        }

        $printSpec = $this->printSpecToPriceRequest->execute(
            $unifiedSaleItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId(),
            array_merge([$unifiedSaleItem], $this->findQuoteItemsChildItems->execute($unifiedSaleItem->getParent(), $unifiedSaleItem))
        );

        $output = [
            [
                'title' => __('Art #%1', $unifiedSaleItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId()),
                'values' => []
            ]
        ];

        $output[] = $this->getLocations($printSpec);

        return $output;
    }

    private function isDecorated(UnifiedSaleItem $unifiedSaleItem): bool
    {
        return $unifiedSaleItem->getExtensionAttributes()
            && $unifiedSaleItem->getExtensionAttributes()->getPrintSpecItem()
            && $unifiedSaleItem->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId();
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
                'print_method' => $this->printMethodResource->getPrintMethodNameFor($location->getPrintMethodId()),
                'name' => $location->getDisplayText()
            ];
        }
        return $locations;
    }
}
