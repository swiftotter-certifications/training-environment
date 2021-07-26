<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Collector;

use Magento\Sales\Model\Order;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use SwiftOtter\OrderExport\Collector\HeaderData as HeaderDataCollector;
use SwiftOtter\OrderExport\Model\HeaderData as HeaderDataModel;

class HeaderDataTest extends TestCase
{
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    public function testHeaderOutputMatches()
    {
        /** @var Order $order */
        include __DIR__ . '/../../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order.php';

        /** @var HeaderDataModel $headerData */
        $headerData = $this->objectManager->get(HeaderDataModel::class);
        $headerData->setMerchantNotes('Test note');
        $headerData->setShipDate(new \DateTime('2019-11-01'));

        /** @var HeaderDataCollector $collector */
        $collector = $this->objectManager->get(HeaderDataCollector::class);
        $output = $collector->collect($order, $headerData);

        $this->assertArraySubset([
            'ship_on' => '01/11/2019',
            'name' => 'firstname lastname',
            'address' => 'street',
            'city' => 'Los Angeles'
        ], $output['shipping']);

        $this->assertEquals('Test note', $output['merchant_notes']);
    }
}