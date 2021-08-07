<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\FetchDefaultPrice as TestSubject;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\LocationRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequest\ProductRequestInterface;
use SwiftOtter\ProductDecorator\Api\Data\PriceRequestInterface;

class FetchDefaultPriceTest extends TestCase
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
        $response = $this->testSubject->execute('simple');
        $this->assertEquals(12.076, $response);
    }
}
