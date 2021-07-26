<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/1/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Service;

use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class Order
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        RequestInterface $request
    ) {
        $this->orderRepository = $orderRepository;
        $this->request = $request;
    }

    public function get(): ?OrderInterface
    {
        return $this->orderRepository->get(
            (int)$this->request->getParam('order_id')
        );
    }
}
