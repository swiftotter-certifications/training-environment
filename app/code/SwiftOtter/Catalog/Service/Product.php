<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use SwiftOtter\Catalog\Model\ResourceModel\ProductLookup;

class Product
{
    private $cache = [];

    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    /** @var array */
    private $attributes;

    /** @var ProductLookup */
    private $productLookup;

    public function __construct(ProductCollectionFactory $productCollectionFactory, array $attributes, ProductLookup $productLookup)
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->attributes = $attributes;
        $this->productLookup = $productLookup;
    }

    public function get(?string $sku): ?ProductInterface
    {
        if (!$sku) {
            return null;
        }

        if (isset($this->cache[$sku])) {
            return $this->cache[$sku];
        }

        $product = $this->productCollectionFactory->create()
            ->addFieldToFilter('sku', $sku)
            ->addAttributeToSelect($this->attributes)
            ->getFirstItem();

        $this->cache[$sku] = $product->getId() ? $product : null;
        return $this->cache[$sku];
    }

    public function getById(int $id): ?ProductInterface
    {
        return $this->get($this->productLookup->getSkuById($id));
    }
}
