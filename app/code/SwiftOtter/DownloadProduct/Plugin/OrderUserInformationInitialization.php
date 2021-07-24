<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 9/17/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin;

use Magento\Sales\Api\Data\OrderItemExtensionInterfaceFactory as OrderItemExtensionFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Item as OrderItemModel;
use Magento\Sales\Model\Order\ItemFactory;
use Magento\Sales\Model\ResourceModel\Metadata;
use SwiftOtter\DownloadProduct\Model\OrderUserInformationFactory;

class OrderUserInformationInitialization
{
    /** @var OrderUserInformationFactory */
    private $userInformationFactory;

    /** @var OrderItemExtensionFactory */
    private $orderItemExtensionFactory;

    public function __construct(
        OrderUserInformationFactory $userInformationFactory,
        OrderItemExtensionFactory $orderItemExtensionFactory
    ) {
        $this->userInformationFactory = $userInformationFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    public function afterCreate(ItemFactory $factory, OrderItemModel $orderItem)
    {
        $this->initialize($orderItem);
        return $orderItem;
    }

    private function initialize(OrderItemModel $orderItem): void
    {
        $extension = $orderItem->getExtensionAttributes() ?: $this->orderItemExtensionFactory->create();
        $extension->setUserInformation($this->userInformationFactory->create());
        $orderItem->setExtensionAttributes($extension);
    }
}
