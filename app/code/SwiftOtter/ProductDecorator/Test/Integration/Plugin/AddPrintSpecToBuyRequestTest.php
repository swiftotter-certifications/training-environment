<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Test\Integration\Plugin;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\DataObject;
use Magento\Quote\Model\QuoteFactory as TestSubject;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class AddPrintSpecToBuyRequestTest extends TestCase
{
    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class)->create();

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        include __DIR__ . '/../_files/everything.php';

        include __DIR__ . '/../_files/print_spec_quote_rollback.php';
        include __DIR__ . '/../_files/print_spec_blank_quote.php';
    }

    public function testAddToCart()
    {
        $product = ObjectManager::getInstance()->get(ProductRepository::class)->get('simple');
        $request = new DataObject([
            "item" => $product->getId(),
            "decorator" => \Safe\json_encode(["products" => [["sku" => "great-tent-1","quantity" => 1]],"locations" => [["location_id" => "1", "print_method_id" => 1,"colors" => ["#ffffff","#000000","#ff0000"],"display_text" => null]]])
        ]);

        $result = $this->testSubject->addProduct($product, $request);
        $this->assertNotNull($result->getExtensionAttributes()->getPrintSpecItem()->getPrintSpecId());
    }
}
