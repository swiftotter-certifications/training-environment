<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Model\Calculator;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use \SwiftOtter\ProductDecorator\Model\Calculator\BasePrice as TestSubject;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequest;
use SwiftOtter\ProductDecorator\Model\Data\PriceRequestProduct;
use SwiftOtter\ProductDecorator\Model\Data\PriceResponse;
use SwiftOtter\ProductDecorator\Model\Data\PriceResponseProduct;
use SwiftOtter\ProductDecorator\Model\Tier;

class BasePriceTest extends TestCase
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
        include __DIR__ . '/../../_files/simple_product.php';
    }

    /**
     * @dataProvider priceReview
     */
    public function testProductPriceMatches($expected, $quantity)
    {
        $product = $this->buildRequestProduct($quantity);

        $response = ObjectManager::getInstance()->create(
            PriceResponseProduct::class,
            ['product' => $product, 'tier' => $this->createMock(Tier::class)]
        );

        $output = $this->testSubject->calculate($this->buildRequest($product), $response);
        $this->assertEquals($expected, $output->getTotal());
    }

    public function priceReview()
    {
        return [
            [10, 1],
            [5, 100]
        ];
    }

    private function buildRequestProduct(int $quantity)
    {
        $product = ObjectManager::getInstance()->create(PriceRequestProduct::class);
        $product->setSku('simple');
        $product->setQuantity($quantity);

        return $product;
    }

    private function buildRequest($product)
    {
        $request = ObjectManager::getInstance()->create(PriceRequest::class);
        $request->setProducts([$product]);

        return $request;
    }

}
