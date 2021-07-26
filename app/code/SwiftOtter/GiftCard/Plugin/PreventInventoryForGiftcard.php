<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Plugin;

use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface;
use Magento\InventorySales\Model\IsProductSalableForRequestedQtyCondition\IsProductSalableForRequestedQtyConditionChain as QtyConditionCheck;
use Magento\InventorySalesApi\Api\Data\ProductSalableResultInterfaceFactory;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class PreventInventoryForGiftcard
{
    /** @var GetProductTypesBySkusInterface */
    private $getProductTypesBySkus;
    /** @var ProductSalableResultInterfaceFactory */
    private $resultFactory;

    public function __construct(
        GetProductTypesBySkusInterface $getProductTypesBySkus,
        ProductSalableResultInterfaceFactory $resultFactory
    ) {
        $this->getProductTypesBySkus = $getProductTypesBySkus;
        $this->resultFactory = $resultFactory;
    }

    public function aroundExecute(
        QtyConditionCheck $subject,
        callable $proceed,
        string $sku,
        ...$args
    ) {
        if ($this->getProductTypesBySkus->execute([$sku])[$sku] === GiftCard::TYPE_CODE) {
            return $this->resultFactory->create(['errors' => []]);
        }

        return $proceed($sku, ...$args);
    }
}