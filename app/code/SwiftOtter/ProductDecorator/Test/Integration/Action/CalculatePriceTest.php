<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\CalculatePrice;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;

class CalculatePriceTest extends TestCase
{
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(CalculatePrice::class);

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../_files/everything.php';
    }

    public function testPriceReturnsCorrect()
    {
        $request = ObjectManager::getInstance()->get(PriceRequestInterface::class);
        $location = ObjectManager::getInstance()->get(LocationRequestInterface::class);
        $location->setColors(['#ffffff']);
        $location->setLocationId(1);

        $request->setLocations([$location]);

        $product = ObjectManager::getInstance()->get(ProductRequestInterface::class);
        $product->setSku('simple');
        $product->setQuantity(1);
        $request->setProducts([$product]);

        $this->testSubject->execute($request);
    }

}
