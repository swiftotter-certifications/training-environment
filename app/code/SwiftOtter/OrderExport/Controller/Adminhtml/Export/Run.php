<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action as BackendAction;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use SwiftOtter\OrderExport\Model\HeaderDataFactory;
use SwiftOtter\OrderExport\Orchestrator;

class Run extends BackendAction implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'SwiftOtter_OrderExport::OrderExport';

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var HeaderDataFactory
     */
    private $headerDataFactory;

    /**
     * @var Orchestrator
     */
    private $orchestrator;

    public function __construct(
        JsonFactory $jsonFactory,
        Action\Context $context,
        HeaderDataFactory $headerDataFactory,
        Orchestrator $orchestrator
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->headerDataFactory = $headerDataFactory;
        $this->orchestrator = $orchestrator;

        parent::__construct($context);
    }

    public function execute()
    {
        $headerData = $this->headerDataFactory->create();
        $headerData->setShipDate(new \DateTime($this->getRequest()->getParam('ship_date') ?? ''));
        $headerData->setMerchantNotes((string)$this->getRequest()->getParam('merchant_notes'));

        $results = $this->orchestrator->run(
            (int)$this->getRequest()->getParam('order_id'),
            $headerData
        );

        $response = $this->jsonFactory->create();

        $response->setData($results);

        return $response;
    }
}