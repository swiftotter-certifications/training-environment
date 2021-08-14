<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge as PrintChargeResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\Source\Colors;
use SwiftOtter\ProductDecorator\Service\Product as ProductService;

class ConfigRenderer implements ArgumentInterface
{
    /** @var LocationPrintMethodResource */
    private $locationPrintMethodResource;

    /** @var LocationResource */
    private $locationResource;

    /** @var PrintMethodResource */
    private $printMethodResource;

    /** @var ProductService */
    private $productService;

    /** @var PrintChargeResource */
    private $printChargeResource;

    /** @var UrlInterface */
    private $url;

    /** @var Colors */
    private $colors;

    public function __construct(
        LocationPrintMethodResource $locationPrintMethodResource,
        LocationResource $locationResource,
        PrintMethodResource $printMethodResource,
        ProductService $productService,
        PrintChargeResource $printChargeResource,
        UrlInterface $url,
        Colors $colors
    ) {
        $this->locationPrintMethodResource = $locationPrintMethodResource;
        $this->locationResource = $locationResource;
        $this->printMethodResource = $printMethodResource;
        $this->productService = $productService;
        $this->printChargeResource = $printChargeResource;
        $this->url = $url;
        $this->colors = $colors;
    }

    public function getConfiguration(): array
    {
        if (!$this->isValid()) {
            return [];
        }

        return [
            'component' => "SwiftOtter_ProductDecorator/js/decorator",
            'data' => [
                'sku' => $this->productService->getProduct()->getSku(),
                'locations' => $this->getLocations(),
                'print_methods' => $this->printMethodResource->getPrintMethodDataPerSkus([$this->productService->getProduct()->getSku()]),
                'location_print_methods' => $this->getPrintMethodsByLocations(),
                'color_limiter' => $this->getMaximumColors(),
                'colors' => $this->getColors(),
                'add_url' => $this->url->getUrl() . 'rest/V1/addtocart',
                'price_url' => $this->url->getUrl() . 'rest/V1/decoration-price'
            ],
            'note' => 'Once you place the order, we will send you proofs to approve before we customize this product.'
        ];
    }

    public function isValid(): bool
    {
        return $this->productService->getProduct() !== null;
    }

    private function getLocations(): array
    {
        return $this->locationPrintMethodResource->getAvailableLocationIds(
            $this->productService->getProduct()->getSku()
        );
    }

    private function getPrintMethodsByLocations(): array
    {
        return $this->locationPrintMethodResource->getPrintMethodIdsGroupedByLocationIds(
            $this->productService->getProduct()->getSku()
        );
    }

    private function getMaximumColors(): array
    {
        $printMethodIds = array_unique(array_merge(...array_values($this->getPrintMethodsByLocations())));
        $priceTypes = array_map(function($printMethodId) {
            return $this->printMethodResource->getPriceType($printMethodId);
        }, $printMethodIds);

        $maxColors = array_map(function($priceType) {
            return $this->printChargeResource->getMaxColorsByTier($priceType);
        }, $priceTypes);

        return \Safe\array_combine($priceTypes, $maxColors);
    }

    private function getColors(): array
    {
        return $this->colors->toOptionArray();
    }
}
