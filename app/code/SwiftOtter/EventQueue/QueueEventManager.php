<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/14/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\EventQueue;

use Magento\AsynchronousOperations\Api\Data\OperationInterfaceFactory;
use Magento\Framework\Bulk\OperationInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;
use SwiftOtter\EventQueue\Action\TriggerEventDispatcher;
use SwiftOtter\EventQueue\Constants;
use SwiftOtter\EventQueue\QueueEventManagerInterface;

class QueueEventManager implements QueueEventManagerInterface
{
    /** @var PublisherInterface */
    private $publisher;

    /** @var OperationInterfaceFactory */
    private $operationFactory;

    /** @var StoreManager */
    private $storeManager;

    /** @var TriggerEventDispatcher */
    private $triggerEventDispatcher;

    public function __construct(
        PublisherInterface $publisher,
        OperationInterfaceFactory $operationFactory,
        StoreManager $storeManager,
        TriggerEventDispatcher $triggerEventDispatcher
    ) {
        $this->publisher = $publisher;
        $this->operationFactory = $operationFactory;
        $this->storeManager = $storeManager;
        $this->triggerEventDispatcher = $triggerEventDispatcher;
    }

    public function dispatch($eventName, array $data = [])
    {
        if ($this->isDev()) {
            $this->triggerEventDispatcher->process($eventName, $data);
            return;
        }

        $this->dispatchThroughQueue($eventName, $data);
    }

    private function dispatchThroughQueue($eventName, array $data = [])
    {
        $operation = $this->operationFactory->create();
        $operation->setSerializedData(json_encode([
            Constants::EVENT_NAME => $eventName,
            Constants::DATA => $data
        ]));
        $operation->setStatus(OperationInterface::STATUS_TYPE_OPEN);
        $operation->setTopicName('event.queue');

        $this->publisher->publish('event.queue', $operation);
    }

    private function isDev(): bool
    {
        $url = $this->storeManager->getStore()->getBaseUrl();

        return stripos($url, 'dev.') !== false
            || stripos($url, 'dev-') !== false
            || stripos($url, 'beta.') !== false
            || stripos($url, 'beta-') !== false
            || stripos($url, 'sandbox.') !== false;
    }
}
