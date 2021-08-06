<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'id' => 1,
        'min_tier' => '1',
        'max_tier' => '5'
    ],
    [
        'id' => 2,
        'min_tier' => '6',
        'max_tier' => '10'
    ],
    [
        'id' => 3,
        'min_tier' => '11',
        'max_tier' => '20'
    ],
    [
        'id' => 4,
        'min_tier' => '21',
        'max_tier' => '40'
    ],
    [
        'id' => 5,
        'min_tier' => '41',
        'max_tier' => '50'
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $tier \SwiftOtter\ProductDecorator\Model\Tier */
$tier = $objectManager->create(\SwiftOtter\ProductDecorator\Model\Tier::class);

foreach ($data as $dataAdd) {
    $tier->getResource()->getConnection()->insert(
        $tier->getResource()->getMainTable(),
        $dataAdd
    );
}
