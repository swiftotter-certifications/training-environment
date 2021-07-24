<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\EventQueue\Action;

use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface;
use SwiftOtter\EventQueue\Constants;

class TriggerEventDispatcher
{
    /** @var EventManager */
    private $eventManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EventManager $eventManager, LoggerInterface $logger)
    {
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function process($eventName, array $data = [])
    {
        try {
            $this->eventManager->dispatch($eventName, $data);
        } catch (\Throwable $throwable) {
            $this->logger->critical('Error thrown when dispatching an event.', [
                    Constants::EVENT_NAME => $eventName,
                    Constants::DATA => $data,
                    'message' => $throwable->getMessage(),
                    'trace' => $throwable->getTraceAsString()
                ]
            );
        }
    }
}
