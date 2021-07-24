<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Action;

use SwiftOtter\Catalog\Model\ResourceModel\ProductLookup;
use SwiftOtter\Checkout\Api\Data\AddToCartDetailsInterface;

class BuildBuyRequestFromDetails
{
    /** @var ProductLookup */
    private $productLookup;

    public function __construct(ProductLookup $productLookup)
    {
        $this->productLookup = $productLookup;
    }

    public function execute(AddToCartDetailsInterface $details): array
    {
        return [
            'qty' => $details->getQty(),
            'product' => $this->productLookup->getIdBySku($details->getSku())
        ];
    }
}
