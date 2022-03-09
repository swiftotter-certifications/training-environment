<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Collector;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\OrderExport\Collector\HeaderData as HeaderDataCollector;
use SwiftOtter\OrderExport\Model\HeaderData as HeaderDataModel;

class HeaderDataTest extends TestCase
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
    public function testHeaderOutputMatches()
    {
        $orderResource = ObjectManager::getInstance()->get(OrderResource::class);
        $order = ObjectManager::getInstance()->get(OrderFactory::class)->create();
        $orderResource->load($order, '100000001', 'increment_id');

        /** @var HeaderDataModel $headerData */
        $headerData = $this->objectManager->get(HeaderDataModel::class);
        $headerData->setMerchantNotes('Test note');
        $headerData->setShipDate(new \DateTime('2019-11-01'));

        /** @var HeaderDataCollector $collector */
        $collector = $this->objectManager->get(HeaderDataCollector::class);
        $output = $collector->collect($order, $headerData);

        $this->assertEquals('01/11/2019', $output['shipping']['ship_on']);
        $this->assertEquals('firstname lastname', $output['shipping']['name']);
        $this->assertEquals('street', $output['shipping']['address']);
        $this->assertEquals('Los Angeles', $output['shipping']['city']);
        $this->assertEquals('Test note', $output['merchant_notes']);
    }
}
