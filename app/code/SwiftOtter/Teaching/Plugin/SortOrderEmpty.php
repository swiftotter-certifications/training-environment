<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 2/11/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Plugin;

use Psr\Log\LoggerInterface;
use SwiftOtter\Teaching\Command\GetProduct;

/**
 * NOTE: it is against Magento best practices to use a plugin against a class in the same module.
 * This is strictly for demonstration purposes.
 *
 * bin/magento teaching:load:product --sku great-tent-1
 *
 * Then, watch the log files.
 */
class SortOrderEmpty
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function afterRun(GetProduct $command, $result)
    {
        $this->logger->alert('Populated sort has now executed.');
        return $result;
    }
}
