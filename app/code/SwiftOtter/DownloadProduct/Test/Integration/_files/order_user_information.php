<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/4/21
 * @website https://swiftotter.com
 **/

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$orderItemCollection = $objectManager->create(\Magento\Sales\Model\ResourceModel\Order\Item\Collection::class);
/** @var \Magento\Sales\Model\Order\Item $orderItem */
$orderItem = $orderItemCollection->getFirstItem();

$userInformation = $objectManager->create(\SwiftOtter\DownloadProduct\Model\OrderUserInformation::class);
$userInformation->setId(1);
$userInformation->setOrderId((int)$orderItem->getOrderId());
$userInformation->setOrderItemId((int)$orderItem->getItemId());
$userInformation->setName('Test Name');
$userInformation->setIsShared(true);
$userInformation->setEmail('joseph@swiftotter.com');

$resource = $objectManager->get(\SwiftOtter\DownloadProduct\Model\ResourceModel\OrderUserInformation::class);
$resource->getConnection()
    ->insert(
        $resource->getMainTable(),
        $userInformation->getData()
    );


