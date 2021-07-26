<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\InvoiceItemInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Constants;
use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CodeGenerator;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class RegisterGiftCard implements ObserverInterface
{
    /** @var OrderItemRepositoryInterface */
    private $orderItemRepository;

    /** @var OrderItemInterface[] */
    private $orderItems = [];

    /** @var ProductInterface[] */
    private $products = [];

    /** @var GiftCardFactory */
    private $giftCardFactory;

    /** @var GiftCardRepository */
    private $giftCardRepository;

    /** @var CodeGenerator */
    private $codeGenerator;

    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        GiftCardFactory $giftCardFactory,
        GiftCardRepository $giftCardRepository,
        CodeGenerator $codeGenerator,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->giftCardFactory = $giftCardFactory;
        $this->giftCardRepository = $giftCardRepository;
        $this->codeGenerator = $codeGenerator;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function execute(Observer $observer)
    {
        /** @var Invoice $invoice */
        $invoice = $observer->getData('invoice');

        $giftcards = array_filter($invoice->getAllItems(), function(InvoiceItemInterface $invoiceItem) {
            $product = $this->getProduct((int)$invoiceItem->getProductId());

            return $product->getTypeId() === GiftCard::TYPE_CODE;
        });

        if (!count($giftcards)) {
            return;
        }

        foreach ($giftcards as $giftcardInvoiceItem) {
            $this->createGiftCard($giftcardInvoiceItem);
        }
    }

    private function createGiftCard(InvoiceItemInterface $giftcardInvoiceItem)
    {
        /** @var GiftCardInterface $giftcard */
        $giftcard = $this->giftCardFactory->create();

        /*
         * NOTE: if this invoice is created at the moment of the sale (authorize and capture payment action),
         * no IDs have yet been specified for the order or invoice item. As such, we can fetch the order item
         * directly from the invoice item. Otherwise, we can load it via the repository.
         */
        $orderItem = $this->getOrderItem($giftcardInvoiceItem, $giftcardInvoiceItem->getOrderItemId());

        $recipientEmail = $orderItem->getProductOptionByCode(Constants::OPTION_RECIPIENT_EMAIL);
        $recipientName = $orderItem->getProductOptionByCode(Constants::OPTION_RECIPIENT_NAME);

        if (!$recipientEmail) {
            throw new NoSuchEntityException(__('The recipient\'s email was not set for this giftcard.'));
        }

        $value = $giftcardInvoiceItem->getQty() * ($orderItem->getRowTotal() / $orderItem->getQtyOrdered());

        $giftcard->setInitialValue($value);
        $giftcard->setCurrentValue($value);
        $giftcard->setRecipientEmail($recipientEmail);
        $giftcard->setRecipientName($recipientName);
        $giftcard->setCode($this->codeGenerator->getNewCode());
        $giftcard->setStatus(\SwiftOtter\GiftCard\Model\GiftCard::STATUS_ACTIVE);

        $this->giftCardRepository->save($giftcard, $orderItem->getStoreId());
    }

    private function getOrderItem(InvoiceItemInterface $invoiceItem, ?int $orderItemId): OrderItemInterface
    {
        if ($invoiceItem->getOrderItem()) {
            return $invoiceItem->getOrderItem();
        }

        if (isset($this->orderItems[$orderItemId])) {
            return $this->orderItems[$orderItemId];
        }

        $this->orderItems[$orderItemId] = $this->orderItemRepository->get($orderItemId);

        return $this->orderItems[$orderItemId];
    }

    private function getProduct(int $productId): ProductInterface
    {
        if (isset($this->products[$productId])) {
            return $this->products[$productId];
        }

        $this->products[$productId] = $this->productCollectionFactory->create()
            ->addFieldToFilter('entity_id', $productId)
            ->getFirstItem();

        return $this->products[$productId];
    }
}