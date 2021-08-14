<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Action\PrintSpec;

use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\ProductDecorator\Action\PrintSpec\PrintSpecToPriceRequest as TestSubject;

class PrintSpecToPrintRequestTest extends TestCase
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
        include __DIR__ . '/../../_files/everything_quote.php';
    }

    public function testPrintSpecIsResurrected()
    {
        $quoteItems = ObjectManager::getInstance()->create(\Magento\Quote\Model\ResourceModel\Quote\Item\Collection::class);

        $priceRequest = $this->testSubject->execute(101, $quoteItems->getItems());
        $this->assertEquals(1, count($priceRequest->getProducts()), 'Products match');
        $this->assertEquals(2, count($priceRequest->getLocations()), 'Locations match');

        $this->assertEquals(1, $priceRequest->getLocations()[0]->getLocationId());

        $this->assertEquals('client_id_1', $priceRequest->getClientId());
    }
}
