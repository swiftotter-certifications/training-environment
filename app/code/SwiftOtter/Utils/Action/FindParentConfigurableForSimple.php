<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/30/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Action;

use SwiftOtter\Utils\Model\ResourceModel\ProductLookup;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;

class FindParentConfigurableForSimple
{
    /** @var ProductLookup */
    private $productLookup;

    /** @var ProductResourceModel */
    private $productResourceModel;

    private $parentSku = [];

    public function __construct(ProductLookup $productLookup, ProductResourceModel $productResourceModel)
    {
        $this->productLookup = $productLookup;
        $this->productResourceModel = $productResourceModel;
    }

    public function execute(string $sku): ?string
    {
        if (isset($this->parentSku[$sku])) {
            return $this->parentSku[$sku];
        }

        $productId = $this->productLookup->getEntityIdFromSku($sku);
        $parents = $this->productLookup->getParentIdsFor($productId);

        $parents = array_filter($parents, function ($parentId) {
            return (bool)$this->productResourceModel->getAttributeRawValue($parentId, 'status', 0);
        });

        // TODO: build other criteria if necessary
        $this->parentSku[$sku] = count($parents) ? $this->productLookup->getSkuFromEntityId((int)reset($parents)) : null;

        return $this->parentSku[$sku];
    }
}
