<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'location_id' => 1,
        'print_method_id' => 1,
        'sku' => 'simple'
    ],
    [
        'location_id' => 1,
        'print_method_id' => 2,
        'sku' => 'simple'
    ],
    [
        'location_id' => 1,
        'print_method_id' => 3,
        'sku' => 'simple'
    ],
    [
        'location_id' => 2,
        'print_method_id' => 1,
        'sku' => 'simple'
    ],
    [
        'location_id' => 2,
        'print_method_id' => 2,
        'sku' => 'simple'
    ],
    [
        'location_id' => 2,
        'print_method_id' => 3,
        'sku' => 'simple'
    ],
    [
        'location_id' => 3,
        'print_method_id' => 1,
        'sku' => 'simple'
    ],
    [
        'location_id' => 3,
        'print_method_id' => 2,
        'sku' => 'simple'
    ],
    [
        'location_id' => 3,
        'print_method_id' => 3,
        'sku' => 'simple'
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $model \SwiftOtter\ProductDecorator\Model\LocationPrintMethod */
$model = $objectManager->create(\SwiftOtter\ProductDecorator\Model\LocationPrintMethod::class);

foreach ($data as $dataAdd) {
    $model->getResource()->getConnection()->insert(
        $model->getResource()->getMainTable(),
        $dataAdd
    );
}
