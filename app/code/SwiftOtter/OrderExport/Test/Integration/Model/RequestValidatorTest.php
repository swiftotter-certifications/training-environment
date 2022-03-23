<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/17/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use SwiftOtter\OrderExport\Model\HeaderData;
use SwiftOtter\OrderExport\Model\RequestValidator;

class RequestValidatorTest extends TestCase
{
    private ?RequestValidator $requestValidator = null;
    private ?HeaderData $headerData = null;

    protected function setUp(): void
    {
        $this->requestValidator = ObjectManager::getInstance()->get(RequestValidator::class);
        $this->headerData = $this->createMock(HeaderData::class);
    }

    public function testValidateReturnsFalseForInvalidOrderId()
    {
        $this->assertEquals(
            false,
            $this->requestValidator->validate(123123123, $this->headerData)
        );
    }

    public function testValidateReturnsFalseForEmptyOrderId()
    {
        $this->assertEquals(
            false,
            $this->requestValidator->validate(0, $this->headerData)
        );
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     *
     * @return void
     */
    public function testValidateReturnsTrueForOrder()
    {
        $searchCriteriaBuilder = ObjectManager::getInstance()->get(SearchCriteriaBuilder::class);
        $repository = ObjectManager::getInstance()->get(OrderRepositoryInterface::class);
        $candidates = $repository->getList(
            $searchCriteriaBuilder->addFilter('increment_id', '100000001')->create()
        )->getItems();

        $order = reset($candidates);

        $this->assertEquals(
            true,
            $this->requestValidator->validate((int)$order->getId(), $this->headerData)
        );
    }
}
