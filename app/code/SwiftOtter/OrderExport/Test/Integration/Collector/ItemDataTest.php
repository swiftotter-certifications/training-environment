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
use SwiftOtter\OrderExport\Collector\ItemData;
use SwiftOtter\OrderExport\Model\HeaderData as HeaderDataModel;

class ItemDataTest extends TestCase
{
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    public function testConfigurableProductOutput()
    {
        include __DIR__ . '/../../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order_configurable_product_rollback.php';

        /** @var Order $order */
        include __DIR__ . '/../../../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/order_configurable_product.php';

        foreach ($order->getItems() as $orderItem) {
            if ($orderItem->getProductType() !== 'simple') {
                continue;
            }

            $orderItem->setSku('simple');
        }

        /** @var HeaderDataModel $headerData */
        $headerData = $this->objectManager->get(HeaderDataModel::class);
        $headerData->setMerchantNotes('Test note');
        $headerData->setShipDate(new \DateTime());

        /** @var ItemData $collector */
        $collector = $this->objectManager->get(ItemData::class);
        $output = $collector->collect($order, $headerData);

        $this->assertEquals([
            [
                'sku' => 'simple',
                'qty' => '2.0000',
                'item_price' => '10.0000',
                'item_cost' => '0.0000',
                'total' => '10.0000'
            ]
        ], $output['items']);
    }
}