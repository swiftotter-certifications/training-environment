<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\UpdateDefaultPrice as TestSubject;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;
use SwiftOtter\ProductDecorator\Attributes;
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;

class UpdateDefaultPriceTest extends TestCase
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
        include __DIR__ . '/../_files/everything.php';
    }

    public function testPriceReturnsCorrect()
    {
        $this->testSubject->execute('simple');
        $product = ObjectManager::getInstance()->get(FastProductRepositoryInterface::class)->get('simple', null, null, null, [Attributes::DISPLAYED_PRICE]);
        $this->assertEquals(12.076, $product->getData(Attributes::DISPLAYED_PRICE));
    }
}
