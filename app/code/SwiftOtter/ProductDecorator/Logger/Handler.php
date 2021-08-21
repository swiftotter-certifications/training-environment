<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/16/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger;

class Handler extends BaseHandler
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::WARNING;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/pricing.log';
}
