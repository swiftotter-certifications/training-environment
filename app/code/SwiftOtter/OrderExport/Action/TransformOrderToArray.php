<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/28/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Action;

use Magento\Sales\Api\OrderRepositoryInterface;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

class TransformOrderToArray
{
    /**
     * @var DataCollectorInterface[]
     */
    private $collectors;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        array $collectors,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->collectors = $collectors;
        $this->orderRepository = $orderRepository;
    }

    public function execute(int $orderId, HeaderData $headerData)
    {
        $order = $this->orderRepository->get($orderId);
        $output = [];

        foreach ($this->collectors as $collector) {
            $output = array_merge($output, $collector->collect($order, $headerData));
        }

        return $output;
    }
}