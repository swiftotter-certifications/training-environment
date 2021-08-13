<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/


$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $collection \Magento\Quote\Model\ResourceModel\Quote\Collection */
$collection = $objectManager->create(\Magento\Quote\Model\ResourceModel\Quote\Collection::class);
foreach ($collection->getItems() as $item) {
    $item->delete();
}
