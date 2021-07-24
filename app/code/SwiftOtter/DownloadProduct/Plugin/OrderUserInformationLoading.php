<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 9/17/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin;

use Magento\Sales\Model\Order\Item as OrderItemModel;
use Magento\Sales\Model\ResourceModel\Order\Item as OrderItemResource;
use SwiftOtter\DownloadProduct\Model\OrderUserInformationFactory;
use SwiftOtter\DownloadProduct\Model\ResourceModel\OrderUserInformation as OrderUserInformationResourceModel;

class OrderUserInformationLoading
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

    public function afterLoad(OrderItemResource $orderItemResource, $resource, OrderItemModel $orderItem)
    {
        if (!$orderItem->getId()) {
            return $resource;
        }

        $orderItem->getExtensionAttributes()->setUserInformation(
            $orderItem->getExtensionAttributes()->getUserInformation() ?: $this->factory->create()
        );

        if ($orderItem->getExtensionAttributes()->getUserInformation()->getEmail() ||
            $orderItem->getExtensionAttributes()->getUserInformation()->getName()) {
            $orderItem->getExtensionAttributes()->getUserInformation()->setOrderId((int)$orderItem->getId());
            return $resource;
        }

        $model = $this->factory->create();
        $this->resourceModel->load($model, $orderItem->getId(), 'order_item_id');

        $orderItem->getExtensionAttributes()->setUserInformation($model);

        return $orderItemResource;
    }

    public function afterSave(OrderItemResource $orderResource, $resource, OrderItemModel $order)
    {
        if (!$order->getId()) {
            return $resource;
        }

        $order->getExtensionAttributes()->setUserInformation(
            $order->getExtensionAttributes()->getUserInformation() ?: $this->factory->create()
        );

        $model = $order->getExtensionAttributes()->getUserInformation();
        if (!$model->getOrderId()) {
            $model->setOrderId((int)$order->getId());
        }

        $this->resourceModel->save($model);

        return $orderResource;
    }
}
