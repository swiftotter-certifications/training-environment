<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger;

class Handler extends BaseHandler
{
    protected $loggerType = Logger::DEBUG;

    protected $fileName = '/var/log/pricing.log';
}
