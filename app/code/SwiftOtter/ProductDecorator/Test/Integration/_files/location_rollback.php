<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $collection \SwiftOtter\ProductDecorator\Model\ResourceModel\Location\Collection */
$collection = $objectManager->create(\SwiftOtter\ProductDecorator\Model\ResourceModel\Location\Collection::class);
foreach ($collection->getItems() as $item) {
    $item->delete();
}
