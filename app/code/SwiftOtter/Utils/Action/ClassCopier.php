<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/10/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Utils\Action;

use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ClassCopier
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute($objectToCopy, $objectToReceive, string $interface)
    {
        $this->validate($objectToCopy, $interface, $objectToReceive);

        $methods = get_class_methods($interface);

        $getters = $this->strip($this->findThoseThatStartWith($methods, 'get'), 'get');
        $setters = $this->strip($this->findThoseThatStartWith($methods, 'set'), 'set');

        $matches = array_intersect($getters, $setters);

        foreach ($matches as $match) {
            $setter = 'set' . $match;
            $getter = 'get' . $match;

            if (!method_exists($objectToCopy, $getter) || !method_exists($objectToReceive, $setter)) {
                continue;
            }

            try {
                $objectToReceive->$setter($objectToCopy->$getter());
            } catch (\Throwable $ex) {
                // silencing exceptions. This is primarily for if there are two arguments for a setter, for example.
                $this->logger->error($ex->getMessage(), [
                    'trace' => $ex->getTraceAsString()
                ]);
            }
        }
    }

    private function strip(array $array, $startsWith)
    {
        return array_map(function ($input) use ($startsWith) {
            if (strpos($input, $startsWith) === 0) {
                return substr($input, strlen($startsWith));
            }

            return $input;
        }, $array);
    }

    private function findThoseThatStartWith(array $array, $start): array
    {
        return array_filter($array, function ($item) use ($start) {
            return strpos($item, $start) === 0;
        });
    }

    private function validate($objectToCopy, string $interface, $objectToReceive): void
    {
        if (!($objectToCopy instanceof $interface)) {
            throw new NoSuchEntityException(__('$objectToCopy is of type %1, %2 expected', get_class($objectToCopy), $interface));
        }

        if (!($objectToReceive instanceof $interface)) {
            throw new NoSuchEntityException(__('$objectToReceive is of type %1, %2 expected', get_class($objectToReceive), $interface));
        }
    }
}
