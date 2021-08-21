<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace Swiftotter\Utils\Model\UnifiedSale;

use Magento\Framework\Model\AbstractModel;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartItemExtensionInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemExtensionInterface;
use Magento\Sales\Model\Order\Item as OrderItem;
use SwiftOtter\Utils\Api\Data\UnifiedSaleInterface;
use SwiftOtter\Utils\Api\Data\UnifiedSaleItemInterface;
use SwiftOtter\Utils\Model\StrictTypeTrait;
use SwiftOtter\Utils\Model\UnifiedSaleFactory;

class Item implements UnifiedSaleItemInterface
{
    const TYPE_QUOTE_ITEM = 'quote_item';
    const TYPE_ORDER_ITEM = 'order_item';

    use StrictTypeTrait;

    /** @var OrderItem|QuoteItem */
    private $item;

    /** @var ItemFactory */
    private $itemFactory;

    /** @var UnifiedSaleFactory */
    private $unifiedSaleFactory;

    public function __construct(
        AbstractModel $item,
        ItemFactory $itemFactory,
        UnifiedSaleFactory $unifiedSaleFactory
    ) {
        $this->item = $item;
        $this->itemFactory = $itemFactory;
        $this->unifiedSaleFactory = $unifiedSaleFactory;
    }

    public function getId(): ?int
    {
        return $this->nullableInt(
            $this->item->{__FUNCTION__}()
        );
    }

    public function getProductId(): ?int
    {
        return $this->nullableInt(
            $this->item->{__FUNCTION__}()
        );
    }

    public function getParentItemId(): ?int
    {
        return $this->nullableInt(
            $this->item->{__FUNCTION__}()
        );
    }

    public function getTotalQty(): int
    {
        return $this->isQuoteItem()
            ? (int)$this->item->getQty()
            : (int)$this->item->getQtyOrdered();
    }

    public function getProductType(): string
    {
        return $this->item->{__FUNCTION__}();
    }

    public function getSku(): string
    {
        return $this->item->{__FUNCTION__}();
    }

    public function getType(): string
    {
        return $this->isQuoteItem()
            ? self::TYPE_QUOTE_ITEM
            : self::TYPE_ORDER_ITEM;
    }

    /**
     * @return UnifiedSaleInterface|null
     */
    public function getParent(): UnifiedSaleInterface
    {
        $object = $this->isQuoteItem()
            ? $this->item->getQuote()
            : $this->item->getOrder();

        if (!$object->getExtensionAttributes()->getUnified()) {
            $object->getExtensionAttributes()->setUnified($this->unifiedSaleFactory->create(['entity' => $object]));
        }

        return $object->getExtensionAttributes()->getUnified();
    }

    /**
     * @return Item|null
     */
    public function getParentItem(): ?UnifiedSaleItemInterface
    {
        if (!$this->item->getParentItem()) {
            return null;
        }

        return $this->itemFactory->create(['item' => $this->item->getParentItem()]);
    }

    public function getCustomPrice(): ?float
    {
        return $this->nullableFloat(
            $this->item->{__FUNCTION__}()
        );
    }

    public function getOriginalCustomPrice(): ?float
    {
        return $this->nullableFloat(
            $this->item->{__FUNCTION__}()
        );
    }

    public function setCustomPrice(?float $price): void
    {
        $this->item->{__FUNCTION__}($price);
    }

    public function setOriginalCustomPrice(?float $price): void
    {
        $this->item->{__FUNCTION__}($price);
    }

    /**
     * @return CartItemExtensionInterface|OrderItemExtensionInterface
     */
    public function getExtensionAttributes()
    {
        return $this->item->{__FUNCTION__}();
    }

    /**
     * @param CartItemExtensionInterface|OrderItemExtensionInterface
     * @return void
     */
    public function setExtensionAttributes($value): void
    {
        $this->item->{__FUNCTION__}($value);
    }

    public function get()
    {
        return $this->item;
    }

    private function isQuoteItem(): bool
    {
        return $this->item instanceof QuoteItem;
    }
}
