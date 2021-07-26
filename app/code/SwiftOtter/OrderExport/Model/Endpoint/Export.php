<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model\Endpoint;

use SwiftOtter\OrderExport\Api\Data\IncomingHeaderDataInterface;
use SwiftOtter\OrderExport\Api\Data\ResponseInterface;
use SwiftOtter\OrderExport\Api\ExportInterface;
use SwiftOtter\OrderExport\Model\ResponseDetailsFactory;
use SwiftOtter\OrderExport\Orchestrator;

class Export implements ExportInterface
{
    /** @var Orchestrator */
    private $orchestrator;

    /** @var ResponseDetailsFactory */
    private $responseDetailsFactory;

    public function __construct(Orchestrator $orchestrator, ResponseDetailsFactory $responseDetailsFactory)
    {
        $this->orchestrator = $orchestrator;
        $this->responseDetailsFactory = $responseDetailsFactory;
    }

    public function execute(int $orderId, IncomingHeaderDataInterface $incomingHeaderData): ResponseInterface
    {
        $headerData = $incomingHeaderData->getHeaderData();

        $results = $this->orchestrator->run(
            $orderId,
            $headerData
        );

        $response = $this->responseDetailsFactory->create();

        $response->setSuccess($results['success']);
        $response->setError((string)$results['error']);

        return $response;
    }
}