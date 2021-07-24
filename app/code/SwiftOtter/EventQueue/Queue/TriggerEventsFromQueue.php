<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/6/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\EventQueue\Queue;

use Magento\AsynchronousOperations\Api\Data\OperationInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use SwiftOtter\EventQueue\Action\TriggerEventDispatcher;
use SwiftOtter\EventQueue\Constants;

class TriggerEventsFromQueue
{
    /** @var LoggerInterface */
    private $logger;

    /** @var State */
    private $state;

    /** @var TriggerEventDispatcher */
    private $eventDispatcher;

    public function __construct(
        TriggerEventDispatcher $eventDispatcher,
        LoggerInterface $logger,
        State $state
    ) {
        $this->logger = $logger;
        $this->state = $state;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function process(OperationInterface $operation)
    {
        try {
            $this->state->setAreaCode(Area::AREA_FRONTEND);
        } catch (LocalizedException $ex) {
        }

        $serializedData = $operation->getSerializedData();
        try {

            $unserializedData = json_decode($serializedData, true);
            if (!isset($unserializedData[Constants::EVENT_NAME]) || !isset($unserializedData[Constants::DATA])) {
                return;
            }

            $eventName = $unserializedData[Constants::EVENT_NAME];
            $data = $unserializedData[Constants::DATA];

            $this->logger->debug('Received payload: ', [
                Constants::EVENT_NAME => $eventName,
                Constants::DATA => $data
            ]);

            $this->eventDispatcher->process($eventName, $data);
        } catch (\InvalidArgumentException $exception) {
            $this->logger->critical('Unable to receive event message: ', [
                'data' => $serializedData,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
        }
    }
}
