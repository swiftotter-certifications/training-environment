<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/2/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CliPerformance;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;

class InitializeCommand
{
    const WHITELIST = [
        \Magento\Setup\Model\ObjectManagerProvider::class,
        \Magento\Setup\Model\ConfigModel::class, // from: \Magento\Setup\Console\Command\ConfigSetCommand
    ];

    public static $objectManager;

    public static function execute(string $className)
    {
        $className = str_replace('\Proxy', '', $className);

        $class = new \ReflectionClass($className);

        $constructor = $class->getConstructor();
        $params = $constructor->getParameters();

        $arguments = [];
        $newConstructor = false;

        try {
            foreach ($params as $param) {
                $type = $param->getClass()->getName();

                if (in_array($type, ['int', 'bool', 'string', 'float'])) {
                    if ($type === 'string') {
                        $arguments[] = (string)"1";
                    } else {
                        $method = $type . "val";
                        $arguments[] = $method(1);
                    }
                } elseif (in_array($type, self::WHITELIST)) {
                    $arguments[] = self::$objectManager->get($type);
                } elseif (strpos($type, '\\') !== false) {
                    $arguments[] = (new \ReflectionClass($type))->newInstanceWithoutConstructor();
                } else {
                    $arguments[] = '';
                }
            }
        } catch (\Throwable $ex) {
            $newConstructor = true;
        }

        $instance = $newConstructor ? $class->newInstanceWithoutConstructor() : new $className(...$arguments); // SwiftOtter Changed

        if (!$newConstructor) {
            return $instance;
        }
        // ensure that name is set.

        try {
            $definition = new \ReflectionProperty(\Symfony\Component\Console\Command\Command::class, 'definition');
            $definition->setAccessible(true);
            $definition->setValue($instance, new \Symfony\Component\Console\Input\InputDefinition());

            $configureMethod = new \ReflectionMethod($instance, 'configure');
            $configureMethod->setAccessible(true);
            $configureMethod->invoke($instance);

            return $instance;
        } catch (\Throwable $ex) {
            return $instance->getName() ? $instance : null;
        }
    }
}
