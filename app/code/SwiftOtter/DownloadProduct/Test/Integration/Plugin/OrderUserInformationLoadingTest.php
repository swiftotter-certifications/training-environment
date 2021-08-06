<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/4/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Test\Integration\Plugin;

use Magento\Sales\Api\OrderItemRepositoryInterface as TestSubject;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as OrderItemCollection;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class OrderUserInformationLoadingTest extends TestCase
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
        include __DIR__ . '/../_files/order_user_information_rollback.php';
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testExtensionAttributesArePopulated()
    {
        include __DIR__ . '/../_files/order_user_information.php';
        $collection = ObjectManager::getInstance()->create(OrderItemCollection::class);
        $orderItemId = $collection->getFirstItem()->getId();

        $orderItem = $this->testSubject->get($orderItemId);
        $this->assertEquals(
            $orderItemId,
            $orderItem->getExtensionAttributes()->getUserInformation()->getOrderItemId(),
            'Order item ID is set on the user information in extension attributes'
        );
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testNoErrorsForEmptyValues()
    {
        $collection = ObjectManager::getInstance()->create(OrderItemCollection::class);
        $orderItemId = $collection->getFirstItem()->getId();

        $orderItem = $this->testSubject->get($orderItemId);
        $this->assertNull($orderItem->getExtensionAttributes()->getUserInformation()->getOrderItemId());
    }
}
