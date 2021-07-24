<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 2/13/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin;

use Laminas\Code\Reflection\ClassReflection;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\ObjectManager\ConfigInterface as Config;
use Magento\Framework\Reflection\NameFinder;
use Magento\Framework\Reflection\TypeProcessor;
use Magento\Framework\Webapi\ServiceInputProcessor;
use Psr\Log\LoggerInterface as Logger;

class PreventErrorsForInvalidMethodsInApi
{
    /** @var Logger */
    private $logger;

    /** @var NameFinder */
    private $nameFinder;

    /** @var Config */
    private $config;

    /** @var TypeProcessor */
    private $typeProcessor;

    /** @var ServiceInputProcessor */
    private $serviceInputProcessor;

    public function __construct(
        Logger $logger,
        NameFinder $nameFinder,
        Config $config,
        TypeProcessor $typeProcessor,
        ServiceInputProcessor $serviceInputProcessor
    ) {
        $this->logger = $logger;
        $this->nameFinder = $nameFinder;
        $this->config = $config;
        $this->typeProcessor = $typeProcessor;
        $this->serviceInputProcessor = $serviceInputProcessor;
    }

    public function beforeConvertValue(ServiceInputProcessor $subject, $data, $type)
    {
        if (!is_array($data)) {
            return null;
        }

        if (!class_exists($type)) {
            return null;
        }

        $class = new ClassReflection($type);
        if (is_subclass_of($type, ServiceInputProcessor::EXTENSION_ATTRIBUTES_TYPE)) {
            $type = substr($type, 0, -strlen('Interface'));
        }

        // Primary method: assign to constructor parameters
        $constructorArgs = $this->getConstructorData($type, $data);

        $output = [];

        foreach ($data as $propertyName => $value) {
            if (isset($constructorArgs[$propertyName])) {
                $output[$propertyName] = $value;
            }

            $camelCaseProperty = SimpleDataObjectConverter::snakeCaseToUpperCamelCase($propertyName);
            try {
                $methodName = $this->nameFinder->getGetterMethodName($class, $camelCaseProperty);
            } catch (\LogicException $ex) {
                continue;
            }

            if (!$class->getMethod($methodName)) {
                continue;
            }

            $output[$propertyName] = $value;
        }

        return [$output, $type];
    }

    private function getConstructorData(string $className, array $data): array
    {
        $preferenceClass = $this->config->getPreference($className);
        $class = new ClassReflection($preferenceClass ?: $className);

        try {
            $constructor = $class->getMethod('__construct');
        } catch (\ReflectionException $e) {
            $constructor = null;
        }

        if ($constructor === null) {
            return [];
        }

        $res = [];
        $parameters = $constructor->getParameters();
        foreach ($parameters as $parameter) {
            if (isset($data[$parameter->getName()])) {
                $parameterType = $this->typeProcessor->getParamType($parameter);

                try {
                    $res[$parameter->getName()] = $this->serviceInputProcessor->convertValue($data[$parameter->getName()], $parameterType);
                } catch (\ReflectionException $e) {
                    // Parameter was not correclty declared or the class is uknown.
                    // By not returing the contructor value, we will automatically fall back to the "setters" way.
                    continue;
                }
            }
        }

        return $res;
    }
}
