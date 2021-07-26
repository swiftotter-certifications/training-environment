<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport;

use SwiftOtter\OrderExport\Action\PushDetailsToWebservice;
use SwiftOtter\OrderExport\Action\SaveExportDetailsToOrder;
use SwiftOtter\OrderExport\Action\TransformOrderToArray;
use SwiftOtter\OrderExport\Model\HeaderData;
use SwiftOtter\OrderExport\Model\RequestValidator;

class Orchestrator
{
    /** @var TransformOrderToArray */
    private $orderToArray;

    /** @var PushDetailsToWebservice */
    private $pushDetailsToWebservice;

    /** @var SaveExportDetailsToOrder */
    private $saveExportDetailsToOrder;

    /** @var RequestValidator */
    private $requestValidator;

    public function __construct(
        RequestValidator $requestValidator,
        TransformOrderToArray $orderToArray,
        PushDetailsToWebservice $pushDetailsToWebservice,
        SaveExportDetailsToOrder $saveExportDetailsToOrder
    ) {
        $this->requestValidator = $requestValidator;
        $this->orderToArray = $orderToArray;
        $this->pushDetailsToWebservice = $pushDetailsToWebservice;
        $this->saveExportDetailsToOrder = $saveExportDetailsToOrder;
    }

    public function run(int $orderId, HeaderData $headerData): array
    {
        $results = ['success' => false, 'error' => null];

        if (!$this->requestValidator->validate($orderId, $headerData)) {
            $results['error'] = (string)__('Invalid order specified.');
            return $results;
        }

        $json = $this->orderToArray->execute($orderId, $headerData);

        try {
            $results['success'] = $this->pushDetailsToWebservice->execute($orderId, $json);
        } catch (\Exception $ex) {
            $results['error'] = $ex->getMessage();
        }

        $this->saveExportDetailsToOrder->execute($orderId, $headerData, $results);

        return $results;
    }
}