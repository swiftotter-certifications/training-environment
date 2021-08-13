<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'id' => 101,
        'name' => 'Test',
        'is_deleted' => 0,
        'client_id' => 'client_id_1'
    ],
    [
        'id' => 102,
        'name' => 'Test Deleted',
        'is_deleted' => 1,
        'client_id' => 'client_id_deleted_1'
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $tier \SwiftOtter\ProductDecorator\Model\Tier */
$tier = $objectManager->create(\SwiftOtter\ProductDecorator\Model\PrintSpec::class);

foreach ($data as $dataAdd) {
    $tier->getResource()->getConnection()->insert(
        $tier->getResource()->getMainTable(),
        $dataAdd
    );
}
