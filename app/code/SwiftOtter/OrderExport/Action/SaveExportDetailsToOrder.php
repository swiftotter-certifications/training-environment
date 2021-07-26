<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/1/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Action;

use Magento\Sales\Model\OrderRepository;
use SwiftOtter\OrderExport\Model\HeaderData;
use SwiftOtter\OrderExport\Model\OrderExportDetailsRepository;

class SaveExportDetailsToOrder
{
    /**
     * @var OrderExportDetailsRepository
     */
    private $repository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderExportDetailsRepository $repository
    ) {
        $this->repository = $repository;
        $this->orderRepository = $orderRepository;
    }

    public function execute(int $orderId, HeaderData $headerData, $results): void
    {
        $order = $this->orderRepository->get($orderId);
        $details = $order->getExtensionAttributes()->getExportDetails();

        if (isset($results['success']) && $results['success'] === true) {
            $details->setExportedAt((new \DateTime())->setTimezone(new \DateTimeZone('UTC')));
        }

        $details->setOrderId($orderId);
        $details->setMerchantNotes($headerData->getMerchantNotes());
        $details->setShipOn($headerData->getShipDate());

        $this->repository->save($details);
    }
}