<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

$data = [
    // EMBROIDERY
    [
        'tier_id' => 1,
        'price' => 2.076,
        'colors' => 1,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 1,
        'price' => 2.250,
        'colors' => 2,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 1,
        'price' => 2.415,
        'colors' => 3,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 1,
        'price' => 2.810,
        'colors' => 4,
        'price_type' => 'embroidery'
    ],

    [
        'tier_id' => 2,
        'price' => 1.870,
        'colors' => 1,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 2,
        'price' => 2.009,
        'colors' => 2,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 2,
        'price' => 2.137,
        'colors' => 3,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 2,
        'price' => 2.465,
        'colors' => 4,
        'price_type' => 'embroidery'
    ],

    [
        'tier_id' => 3,
        'price' => 1.685,
        'colors' => 1,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 3,
        'price' => 1.794,
        'colors' => 2,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 3,
        'price' => 1.891,
        'colors' => 3,
        'price_type' => 'embroidery'
    ],
    [
        'tier_id' => 3,
        'price' => 2.162,
        'colors' => 4,
        'price_type' => 'embroidery'
    ],




    // PAINT:
    [
        'tier_id' => 1,
        'price' => 3.572,
        'colors' => 1,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 1,
        'price' => 4.954,
        'colors' => 2,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 1,
        'price' => 5.934,
        'colors' => 3,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 1,
        'price' => 6.804,
        'colors' => 4,
        'price_type' => 'paint'
    ],

    [
        'tier_id' => 2,
        'price' => 3.218,
        'colors' => 1,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 2,
        'price' => 4.423,
        'colors' => 2,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 2,
        'price' => 5.251,
        'colors' => 3,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 2,
        'price' => 5.969,
        'colors' => 4,
        'price_type' => 'paint'
    ],

    [
        'tier_id' => 3,
        'price' => 2.900,
        'colors' => 1,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 3,
        'price' => 3.950,
        'colors' => 2,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 3,
        'price' => 4.647,
        'colors' => 3,
        'price_type' => 'paint'
    ],
    [
        'tier_id' => 3,
        'price' => 5.236,
        'colors' => 4,
        'price_type' => 'paint'
    ]
];

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var $model \SwiftOtter\ProductDecorator\Model\PrintCharge */
$model = $objectManager->create(\SwiftOtter\ProductDecorator\Model\PrintCharge::class);

foreach ($data as $dataAdd) {
    $model->getResource()->getConnection()->insert(
        $model->getResource()->getMainTable(),
        $dataAdd
    );
}
