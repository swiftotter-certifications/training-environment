<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Action;

use Magento\Sales\Model\Order;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use SwiftOtter\OrderExport\Action\TransformOrderToArray;
use SwiftOtter\OrderExport\Model\HeaderData;

class TransformOrderToArrayTest extends TestCase
{
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    public function testRunsSuccessfully()
    {
        /** @var Order $order */
        include __DIR__ . '/../../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order.php';

        /** @var HeaderData $headerData */
        $headerData = $this->objectManager->get(HeaderData::class);
        $headerData->setShipDate(new \DateTime('2019-11-01'));

        /** @var TransformOrderToArray $action */
        $action = $this->objectManager->get(TransformOrderToArray::class);
        $output = $action->execute((int)$order->getId(), $headerData);

        $this->assertArraySubset([
            'ship_on' => '01/11/2019',
            'name' => 'firstname lastname',
            'city' => 'Los Angeles'
        ], $output['shipping']);

        $this->assertEquals(1, count($output['items']));
    }
}