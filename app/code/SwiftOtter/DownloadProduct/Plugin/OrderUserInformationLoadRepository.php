<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 9/17/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin;

use Magento\Sales\Api\Data\OrderItemInterface as OrderItem;
use Magento\Sales\Model\Order\Item as OrderItemModel;
use Magento\Sales\Model\Order\ItemRepository;
use Magento\Sales\Model\ResourceModel\Order\Item as OrderItemResource;
use SwiftOtter\DownloadProduct\Model\OrderUserInformationFactory;
use SwiftOtter\DownloadProduct\Model\ResourceModel\OrderUserInformation as OrderUserInformationResourceModel;

class OrderUserInformationLoadRepository
{
    /** @var OrderUserInformationFactory */
    private $factory;

    /** @var OrderUserInformationResourceModel */
    private $resourceModel;

    public function __construct(
        OrderUserInformationFactory $factory,
        OrderUserInformationResourceModel $resourceModel
    ) {
        $this->factory = $factory;
        $this->resourceModel = $resourceModel;
    }

    public function afterGet(ItemRepository $itemRepository, OrderItem $orderItem)
    {
        $this->initialize($orderItem);
        return $orderItem;
    }

    public function afterGetList(ItemRepository $itemRepository, $items)
    {
        foreach ($items as $item) {
            $this->initialize($item);
        }

        return $items;
    }

    private function initialize(OrderItem $orderItem)
    {
        $orderItem->getExtensionAttributes()->setUserInformation(
            $orderItem->getExtensionAttributes()->getUserInformation() ?: $this->factory->create()
        );

        if ($orderItem->getExtensionAttributes()->getUserInformation()->getEmail() ||
            $orderItem->getExtensionAttributes()->getUserInformation()->getName()) {
            $orderItem->getExtensionAttributes()->getUserInformation()->setOrderId((int)$orderItem->getId());
            return $orderItem;
        }

        $model = $this->factory->create();
        $this->resourceModel->load($model, $orderItem->getId(), 'order_item_id');

        $orderItem->getExtensionAttributes()->setUserInformation($model);
    }

}
