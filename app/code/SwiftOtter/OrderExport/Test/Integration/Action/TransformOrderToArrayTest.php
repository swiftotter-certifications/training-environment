<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Action;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\OrderExport\Action\TransformOrderToArray;
use SwiftOtter\OrderExport\Model\HeaderData;

class TransformOrderToArrayTest extends TestCase
{
    private $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @return void
     */
    public function testRunsSuccessfully()
    {
        include __DIR__ . '/path/to/fixture.php';
        $orderResource = ObjectManager::getInstance()->get(OrderResource::class);
        $order = ObjectManager::getInstance()->get(OrderFactory::class)->create();
        $orderResource->load($order, '100000001', 'increment_id');

        /** @var HeaderData $headerData */
        $headerData = $this->objectManager->get(HeaderData::class);
        $headerData->setShipDate('2019-11-01');

        /** @var TransformOrderToArray $action */
        $action = $this->objectManager->get(TransformOrderToArray::class);
        $output = $action->execute((int)$order->getId(), $headerData);

        $this->assertEquals('2019-11-01', $output['shipping']['ship_on']);
        $this->assertEquals('firstname lastname', $output['shipping']['name']);
        $this->assertEquals('Los Angeles', $output['shipping']['city']);
        $this->assertEquals(1, count($output['items']));
    }
}
