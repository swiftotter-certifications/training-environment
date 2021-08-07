<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use SwiftOtter\ProductDecorator\Action\UpdateDefaultPrice;
use SwiftOtter\ProductDecorator\Attributes;

class UpdateDisplayedPriceAfterProductSave
{
    /** @var UpdateDefaultPrice */
    private $updateDefaultPrice;

    public function __construct(UpdateDefaultPrice $updateDefaultPrice)
    {
        $this->updateDefaultPrice = $updateDefaultPrice;
    }

    public function afterSave(ProductResource $subject, ProductResource $return, ProductInterface $product): ProductResource
    {
        $displayedPrice = $this->updateDefaultPrice->execute($product->getSku());
        $product->setData(Attributes::DEFAULT_DECORATION_CHARGE, $displayedPrice);

        return $return;
    }
}
