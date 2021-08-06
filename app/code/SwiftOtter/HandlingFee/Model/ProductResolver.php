<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use SwiftOtter\HandlingFee\Attributes;

class ProductResolver
{
    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    private $productCache = [];

    private const ATTRIBUTES = [
        Attributes::CASE_CUBIC_SIZE,
        Attributes::CASE_PACK_QTY
    ];

    public function __construct(ProductCollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function get(string $sku): ?Product
    {
        if (isset($this->productCache[$sku])) {
            return $this->productCache[$sku];
        }

        $product = $this->productCollectionFactory
            ->create()
            ->addAttributeToSelect(self::ATTRIBUTES)
            ->addFieldToFilter('sku', $sku)
            ->getFirstItem();

        $this->productCache[$sku] = $product->getId() ? $product : null;

        return $this->productCache[$sku];
    }
}
