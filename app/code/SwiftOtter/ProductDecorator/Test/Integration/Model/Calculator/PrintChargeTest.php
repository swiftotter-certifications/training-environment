<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Model\Calculator;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Model\Calculator\PrintCharge as TestSubject;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequest;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequestLocation;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequestProduct;
use SwiftOtter\ProductDecorator\Model\Data\PriceResponse;
use SwiftOtter\ProductDecorator\Model\Data\PriceResponseProduct;
use SwiftOtter\ProductDecorator\Model\Tier;

class PrintChargeTest extends TestCase
{
    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../../_files/everything.php';
    }

    /**
     * @dataProvider priceReview
     */
    public function testProductPriceMatches($expected, $quantity, $printMethodId, $colorCount, $tierId)
    {
        $product = $this->buildRequestProduct($quantity);

        $tier = $this->createMock(Tier::class);
        $tier->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($tierId);

        $response = ObjectManager::getInstance()->create(
            PriceResponseProduct::class,
            ['product' => $product, 'tier' => $tier]
        );

        $output = $this->testSubject->calculate($this->buildRequest($product, $printMethodId, $colorCount), $response);
        $this->assertEquals($expected, $output->getTotal());
    }

    public function priceReview()
    {
        return [
            [10, 1, 1, 1, 1],
        ];
    }

    private function buildRequestProduct(int $quantity)
    {
        $product = ObjectManager::getInstance()->create(PriceRequestProduct::class);
        $product->setSku('simple');
        $product->setQuantity($quantity);

        return $product;
    }

    private function buildRequest($product, int $printMethodId, int $colorCount)
    {
        $request = ObjectManager::getInstance()->create(PriceRequest::class);
        $request->setProducts([$product]);

        $location = $this->createMock(PriceRequestLocation::class);
        $location->expects($this->atLeastOnce())
            ->method('getPrintMethodId')
            ->willReturn($printMethodId);

        $location->expects($this->atLeastOnce())
            ->method('getColors')
            ->willReturn(array_fill(0, $colorCount, 1));

        $location->expects($this->any())
            ->method('getLocationId')
            ->willReturn(1);

        $request->setLocations([$location]);

        return $request;
    }

}
