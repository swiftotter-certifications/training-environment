<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Test\Integration\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\Teaching\Api\OrderStageInterface;
use SwiftOtter\Teaching\Model\StageFactory;

class StageFactoryTest extends TestCase
{
    private $target = null;

    protected function setUp(): void
    {
        $this->target = ObjectManager::getInstance()->create(StageFactory::class);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @return void
     */
    public function testOrderReturnsCorrectType()
    {
        $items = ObjectManager::getInstance()->get(OrderRepositoryInterface::class)->getList(
            ObjectManager::getInstance()->get(SearchCriteriaBuilder::class)->addFilter('increment_id', '100000001')->create()
        )->getItems();

        $item = reset($items);
        $output = $this->target->create($item);
        $this->assertTrue($output instanceof OrderStageInterface);
    }
}
