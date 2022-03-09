<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/1/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Test\Integration\Controller\Adminhtml\Export;

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderRepository;
use Magento\TestFramework\TestCase\AbstractBackendController;

/**
 * @magentoDbIsolation enabled
 * @magentoAppArea adminhtml
 * @magentoDataFixture Magento/Sales/_files/order.php
 */
class RunTest extends AbstractBackendController
{
    private $orderRepository;

    protected $uri = 'backend/order_export/export/run';

    protected $httpMethod = HttpRequest::METHOD_POST;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->_objectManager->get(OrderRepository::class);
    }

    public function testExecuteRunsAsExpected()
    {
        $this->prepareRequest();
        $this->dispatch($this->uri);

        $response = $this->getResponse();

        $output = json_decode($response->getBody(), true);
        $this->assertEquals([
            'success' => true,
            'error' => null
        ], $output);

        $order = $this->getOrder('100000001');
        $this->assertEquals(
            'Notes go here',
            $order->getExtensionAttributes()->getExportDetails()->getMerchantNotes()
        );
    }

    private function getOrder(string $incrementalId)
    {
        /** @var SearchCriteria $searchCriteria */
        $searchCriteria = $this->_objectManager->create(SearchCriteriaBuilder::class)
            ->addFilter(OrderInterface::INCREMENT_ID, $incrementalId)
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria)->getItems();
        /** @var OrderInterface|null $order */
        $order = reset($orders);

        return $order;
    }

    private function prepareRequest()
    {
        $order = $this->getOrder('100000001');
        $this->getRequest()->setParams([
            'order_id' => $order->getEntityId(),
            'ship_date' => '2019/12/01',
            'merchant_notes' => 'Notes go here'
        ]);

        $this->getRequest()->setMethod(HttpRequest::METHOD_POST);

        return $order;
    }
}
