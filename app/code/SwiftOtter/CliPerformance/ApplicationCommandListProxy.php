<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/2/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CliPerformance;

use Magento\Setup\Console\CommandList as OriginalCommandList;

class ApplicationCommandListProxy
{
    private $serviceManager;

    /** @var string */
    private $proxyClass;

    private $args;

    public function __construct(string $proxyClass, ...$args)
    {
        $this->proxyClass = $proxyClass;
        $this->args = $args;
    }

    public function getCommands()
    {
        $commands = [];

        $originalCommandList = new $this->proxyClass(...$this->args);

        $class = new \ReflectionClass($originalCommandList);
        $method = $class->getMethod('getCommandsClasses');
        $method->setAccessible(true);

        foreach ($method->invoke($originalCommandList) as $class) {
            if (class_exists($class)) {
                $commands[] = InitializeCommand::execute($class);// SwiftOtter Changed
            } else {
                // phpcs:ignore Magento2.Exceptions.DirectThrow
                throw new \Exception('Class ' . $class . ' does not exist');
            }
        }

        return array_filter($commands);
    }
}
