<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Repository\Test\Integration\Model;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\Repository\Model\FastProductRepository as TestSubject;

class FastProductRepositoryTest extends TestCase
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
        include __DIR__ . '/../_files/simple_product.php';
    }

    public function testGetById()
    {
        $product = $this->testSubject->getById(1, null, null, null, ['name']);
        $this->assertEquals('Simple Product', $product->getName());
    }

    public function testGetByIdAfterFirstLoadingFromRepository()
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        $productRepository = ObjectManager::getInstance()->get(ProductRepository::class);
        $productRepository->get('simple');

        // Product should be loaded from the repository's cache here:
        $product = $this->testSubject->getById(1, null, null, null, ['name']);
        $this->assertEquals('Simple Product', $product->getName());

        // Now, the product should be already loaded, weight is a part and no extra database queries required
        $product = $this->testSubject->getById(1, null, null, null, ['weight']);
        $this->assertEquals(1, $product->getWeight());
    }

    public function testGetLoadsProductWithNoAttributesSpecifiedGetsEverything()
    {
        $product = $this->testSubject->get('simple');
        $this->assertEquals('Simple Product', $product->getName());
    }

    public function testGetLoadsProductWithNameAttributeOnlyGetsName()
    {
        $product = $this->testSubject->get('simple', null, null, null, ['name']);
        $this->assertEquals('Simple Product', $product->getName());
        $this->assertNull($product->getWeight());
    }

    public function testMultipleProductLoads()
    {
        $product = $this->testSubject->get('simple', null, null, null, ['name']);
        $this->assertEquals('Simple Product', $product->getName());
        $this->assertNull($product->getWeight());

        $product = $this->testSubject->get('simple', null, null, null, ['weight']);
        $this->assertEquals(1, $product->getWeight());
    }

    public function testMultipleAttributeLoads()
    {
        $product = $this->testSubject->get('simple', null, null, null, ['name', 'weight']);
        $this->assertEquals('Simple Product', $product->getName());
        $this->assertEquals(1, $product->getWeight());
    }

    public function testGetListWithOneAttribute()
    {
        $searchCriteriaBuilder = ObjectManager::getInstance()->get(SearchCriteriaBuilder::class);

        $results = $this->testSubject->getList($searchCriteriaBuilder->create(), ['name']);
        $items = $results->getItems();
        $product = reset($items);

        $this->assertEquals('Simple Product', $product->getName());
        $this->assertEquals(null, $product->getWeight());
    }

    public function testGetListWithOneAttributeAtATime()
    {
        $searchCriteriaBuilder = ObjectManager::getInstance()->get(SearchCriteriaBuilder::class);

        $this->testSubject->getList($searchCriteriaBuilder->create(), ['name']);
        $results = $this->testSubject->getList($searchCriteriaBuilder->create(), ['weight']);

        $items = $results->getItems();
        $product = reset($items);

        $this->assertEquals('Simple Product', $product->getName());
        $this->assertEquals(1, $product->getWeight());
    }
}
