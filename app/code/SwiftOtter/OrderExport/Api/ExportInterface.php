<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api;

use SwiftOtter\OrderExport\Api\Data\IncomingHeaderDataInterface;
use SwiftOtter\OrderExport\Api\Data\ResponseInterface;

interface ExportInterface
{
    /**
     * @param int $orderId
     * @param \SwiftOtter\OrderExport\Api\Data\IncomingHeaderDataInterface $incomingHeaderData
     * @return \SwiftOtter\OrderExport\Api\Data\ResponseInterface
     */
    public function execute(int $orderId, IncomingHeaderDataInterface $incomingHeaderData): ResponseInterface;
}