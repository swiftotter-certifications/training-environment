<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Model;

use SwiftOtter\Utils\Api\Data\UnifiedSaleInterface;
use SwiftOtter\Utils\Model\UnifiedSale\Item;
use SwiftOtter\Utils\Model\UnifiedSale\ItemFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory as QuoteItemCollectionFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;

class UnifiedSale implements UnifiedSaleInterface
{
    use StrictTypeTrait;

    /** @var Quote|Order */
    private $entity;

    /** @var QuoteItemCollectionFactory */
    private $quoteItemCollectionFactory;

    /** @var OrderItemCollectionFactory */
    private $orderItemCollectionFactory;

    private $items;

    /** @var ItemFactory */
    private $itemFactory;

    public function __construct(
        QuoteItemCollectionFactory $quoteItemCollectionFactory,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        ItemFactory $itemFactory,
        AbstractModel $entity
    ) {
        $this->entity = $entity;
        $this->quoteItemCollectionFactory = $quoteItemCollectionFactory;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->itemFactory = $itemFactory;
    }

    public function getCustomerId(): ?int
    {
        return $this->nullableInt($this->entity->{__FUNCTION__}());
    }

    public function getId(): ?int
    {
        return $this->nullableInt($this->entity->{__FUNCTION__}());
    }

    public function getEntityId(): ?int
    {
        return $this->nullableInt($this->entity->{__FUNCTION__}());
    }

    /**
     * @return Item[]
     */
    public function getAllItems(): ?array
    {
        if ($this->items) {
            return $this->items;
        }

        if ($this->getType() === self::TYPE_QUOTE) {
            $itemCollection = $this->quoteItemCollectionFactory->create()
                ->addFieldToFilter('quote_id', ['eq' => $this->getId()]);
        } else {
            $itemCollection = $this->orderItemCollectionFactory->create()
                ->addFieldToFilter('order_id', ['eq' => $this->getId()]);
        }

        $this->items = array_map(function($item) {
            return $this->itemFactory->create(['item' => $item]);
        }, $itemCollection->getItems());

        return $this->items;
    }

    public function getIncrementId(): string
    {
        return $this->isQuote()
            ? $this->entity->getReservedOrderId()
            : $this->entity->{__FUNCTION__}();
    }

    public function get()
    {
        return $this->entity;
    }

    public function getType(): string
    {
        return $this->isQuote()
            ? self::TYPE_QUOTE
            : self::TYPE_ORDER;
    }

    private function isQuote(): bool
    {
        return $this->entity instanceof Quote;
    }
}
