<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use SwiftOtter\Utils\Model\ResourceModel\ProductLookup;

class UpdateDefaultPrice
{
    /** @var ProductLookup */
    private $productLookup;

    public function __construct(ProductLookup $productLookup)
    {
        $this->productLookup = $productLookup;
    }

    public function execute(string $sku): void
    {
        $price = 1;
        $this->productLookup->saveAttribute($sku, 'price', $price);
    }
}
