<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Model;

use Magento\Sales\Api\Data\OrderInterface;
use SwiftOtter\Teaching\Api\OrderStageInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterface;
use SwiftOtter\Teaching\Api\ProcessorResponseInterfaceFactory;
use SwiftOtter\Teaching\Api\StageProcessorInterface;

class OrderStage implements StageProcessorInterface, OrderStageInterface
{
    private OrderInterface $order;
    private ProcessorResponseInterfaceFactory $responseFactory;

    public function __construct(
        OrderInterface $order,
        ProcessorResponseInterfaceFactory $responseFactory
    ) {
        $this->order = $order;
        $this->responseFactory = $responseFactory;
    }

    public function execute(): ProcessorResponseInterface
    {
        // do something
        return $this->responseFactory->create(['success' => true, 'details' => 'Nothing (order)']);
    }
}
