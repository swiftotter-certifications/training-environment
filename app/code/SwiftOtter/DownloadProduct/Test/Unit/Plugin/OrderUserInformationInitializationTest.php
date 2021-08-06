<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/4/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Test\Unit\Plugin;

use Magento\Sales\Model\Order\Item as OrderItemModel;
use Magento\Sales\Model\Order\ItemFactory as OrderItemFactory;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\DownloadProduct\Plugin\OrderUserInformationInitialization as TestSubject;

class OrderUserInformationInitializationTest extends TestCase
{
    /** @var TestSubject */
    private $testSubject;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->testSubject = ObjectManager::getInstance()->get(TestSubject::class);

        parent::__construct($name, $data, $dataName);
    }

    public function testOrderItemHasExtensionAttributes()
    {
        $orderItem = $this->getMockBuilder(OrderItemModel::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes', 'setExtensionAttributes'])
            ->getMock();

        $orderItem->expects($this->any())
            ->method('getExtensionAttributes')
            ->willReturn(null);

        $orderItem->expects($this->once())
            ->method('setExtensionAttributes')
            ->willReturn(null);

        $this->testSubject->afterCreate(
            ObjectManager::getInstance()->get(OrderItemFactory::class),
            $orderItem
        );
    }

    public function testAssertTrue()
    {
        $this->assertTrue(true, "True is true, surprisingly!");
    }
}
