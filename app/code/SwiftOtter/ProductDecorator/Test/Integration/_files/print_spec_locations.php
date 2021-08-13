<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/13/21
 * @website https://swiftotter.com
 **/

$data = [
    [
        'location_id' => 1,
        'print_method_id' => 1,
        'print_spec_id' => 101,
        'colors' => '["#ffffff"]',
        'display_text' => 'Sample display #1'
    ],
    [
        'location_id' => 2,
        'print_method_id' => 2,
        'print_spec_id' => 101,
        'colors' => '["#ffffff"]',
        'display_text' => 'Sample display #2'
    ],
    [
        'location_id' => 1,
        'print_method_id' => 1,
        'print_spec_id' => 102,
        'colors' => '["#ffffff"]',
        'display_text' => 'Sample display #3, DELETED'
    ],
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $tier \SwiftOtter\ProductDecorator\Model\Tier */
$tier = $objectManager->create(\SwiftOtter\ProductDecorator\Model\PrintSpec\Location::class);

foreach ($data as $dataAdd) {
    $tier->getResource()->getConnection()->insert(
        $tier->getResource()->getMainTable(),
        $dataAdd
    );
}
