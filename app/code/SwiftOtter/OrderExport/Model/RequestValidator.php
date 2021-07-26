<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/1/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\OrderRepository;

class RequestValidator
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function validate(int $orderId, HeaderData $headerData)
    {
        try {
            $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $ex) {
            return false; // order not found
        } catch (InputException $ex) {
            return false; // no ID specified
        }

        return true;
    }
}