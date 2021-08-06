<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/4/21
 * @website https://swiftotter.com
 **/

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$resource = $objectManager->get(\SwiftOtter\DownloadProduct\Model\ResourceModel\OrderUserInformation::class);
$resource->getConnection()
    ->delete(
        $resource->getMainTable()
    );


