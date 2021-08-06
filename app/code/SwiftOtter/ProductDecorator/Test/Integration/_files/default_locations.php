<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'id' => 1,
        'name' => 'Front',
        'code' => 'front',
        'sort_order' => 1
    ],
    [
        'id' => 2,
        'name' => 'Left',
        'code' => 'left',
        'sort_order' => 2
    ],
    [
        'id' => 3,
        'name' => 'Right',
        'code' => 'right',
        'sort_order' => 3
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $location \SwiftOtter\ProductDecorator\Model\Location */
$location = $objectManager->create(\SwiftOtter\ProductDecorator\Model\Location::class);

foreach ($data as $dataAdd) {
    $location->getResource()->getConnection()->insert(
        $location->getResource()->getMainTable(),
        $dataAdd
    );
}
