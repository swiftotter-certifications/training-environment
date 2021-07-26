<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Service;

use Magento\Framework\App\RequestInterface as Request;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface as OrderRepository;

class Order
{
    /** @var Request */
    private $request;

    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(Request $request, OrderRepository $orderRepository)
    {
        $this->request = $request;
        $this->orderRepository = $orderRepository;
    }

    public function get(): OrderInterface
    {
        if ($this->request->getFullActionName() !== 'sales_order_view') {
            throw new NoSuchEntityException(__('You must be in sales_order_view to get an accurate order ID.'));
        }

        return $this->orderRepository->get((int)$this->request->getParam('id'));
    }
}