<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'id' => 1,
        'name' => 'Embroidery',
        'price_type' => 'embroidery'
    ],
    [
        'id' => 2,
        'name' => 'Paint',
        'price_type' => 'paint'
    ],
    [
        'id' => 3,
        'name' => 'Hand-sewn stripes',
        'price_type' => 'handsewn_stripes'
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $model \SwiftOtter\ProductDecorator\Model\PrintMethod */
$model = $objectManager->create(\SwiftOtter\ProductDecorator\Model\PrintMethod::class);

foreach ($data as $dataAdd) {
    $model->getResource()->getConnection()->insert(
        $model->getResource()->getMainTable(),
        $dataAdd
    );
}
