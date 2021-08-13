<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\HydratePriceRequestFromJson as TestSubject;

class HydratePriceRequestFromJsonTest extends TestCase
{
    const INCOMING = [
        'products' => [
            [
                'sku' => 'great-tent-1',
                'quantity' => 1,
            ],
        ],
        'locations' => [
            [
                'location_id' => '1',
                'print_method_id' => 1,
                'colors' =>
                    [
                        0 => '#ffffff',
                        1 => '#000000',
                    ],
                'display_text' => 'asdf',
            ],
        ]
    ];

    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    public function testExecute()
    {
        $priceRequest = $this->testSubject->execute(self::INCOMING);
        $this->assertEquals('great-tent-1', $priceRequest->getProducts()[0]->getSku());
        $this->assertEquals(1, $priceRequest->getLocations()[0]->getLocationId());
    }
}
