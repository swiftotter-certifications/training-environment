<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Api;

use Magento\Sales\Api\Data\OrderInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

interface DataCollectorInterface
{
    public function collect(OrderInterface $order, HeaderData $headerData): array;
}