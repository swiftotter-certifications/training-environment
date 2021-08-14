<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/11/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action\PrintSpec;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PriceRequestToPrintSpec as TestSubject;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;

class PriceRequestToPrintSpecTest extends TestCase
{
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../_files/everything.php';
    }


    public function testExecute()
    {
        $priceRequest = ObjectManager::getInstance()->get(PriceRequestInterface::class);

        $locationRequest = ObjectManager::getInstance()->get(LocationRequestInterface::class);
        $locationRequest->setColors(["#FFFFFF"]);
        $locationRequest->setDisplayText("Some text");
        $locationRequest->setPrintMethodId(1);
        $locationRequest->setLocationId(1);

        $priceRequest->setLocations([$locationRequest]);

        $productRequest = ObjectManager::getInstance()->get(ProductRequestInterface::class);
        $productRequest->setSku('simple');
        $productRequest->setQuantity(1);

        $priceRequest->setProducts([$productRequest]);

        $printSpec = $this->testSubject->execute($priceRequest);
    }
}
