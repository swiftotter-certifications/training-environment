<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin;

use Psr\Log\LoggerInterface;
use SwiftOtter\DownloadProduct\Api\PurchaseDetailsInterface;
use SwiftOtter\DownloadProduct\Model\Purchase as PurchaseTarget;
use SwiftOtter\DownloadProduct\Model\Purchase\DetailsFactory;

class PlaceOrderExceptionHandling
{
    private DetailsFactory $detailsFactory;
    private LoggerInterface $logger;

    public function __construct(
        DetailsFactory $detailsFactory,
        LoggerInterface $logger
    ) {
        $this->detailsFactory = $detailsFactory;
        $this->logger = $logger;
    }

    public function aroundPlaceOrder(PurchaseTarget $subject, callable $proceed): PurchaseDetailsInterface
    {
        try {
            return $proceed();
        } catch (\Exception $exception) {
            $error = [
                'Error: ' . $exception->getMessage(),
                'Trace: ',
                $exception->getTraceAsString(),
                'Email: ' . $subject->getOrderDetails()->getEmail(),
                'Name: ' . $subject->getOrderDetails()->getName(),
                'ProductIdentifer: ' . $subject->getOrderDetails()->getProductIdentifier(),
            ];
            $this->logger->critical($exception->getMessage(), $error);
            mail('joseph@swiftotter.com', 'Error', implode("\n", $error), 'From: Joseph Maxwell <joseph@swiftotter.com>');

            return $this->detailsFactory->create([
                'success' => false,
                'orderId' => 'none',
                'title' => __('There was a problem with your order.'),
                'message' => $exception->getMessage(),
                'purchaseType' => 'error',
                'video' => '',
                'testId' => ''
            ]);
        }
    }
}
