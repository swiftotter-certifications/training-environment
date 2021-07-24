<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/13/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model\Purchase;

use Magento\Framework\Pricing\Helper\Data;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use SwiftOtter\DownloadProduct\Api\PurchaseDetailsInterface;

class Details implements PurchaseDetailsInterface
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    private $orderId;

    /** @var string */
    private $title;

    /** @var string */
    private $message;

    /** @var string */
    private $purchaseType;

    /** @var \Magento\Sales\Api\Data\OrderInterface|null */
    private $order;

    /** @var false|mixed */
    private $success;

    /** @var Data */
    private $priceFormatter;

    /** @var string */
    private $video;

    /** @var string */
    private $testId;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Data $priceFormatter,
        LoggerInterface $logger,
        $orderId = '',
        $message = null,
        $title = null,
        $purchaseType = null,
        $success = false,
        $video = '',
        $testId = ''
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderId = $orderId;
        $this->message = $message;
        $this->success = $success;
        $this->priceFormatter = $priceFormatter;
        $this->title = $title;
        $this->purchaseType = $purchaseType;

        if ($success) {
            try {
                $this->order = $this->orderRepository->get($this->orderId);
            } catch (\Exception $ex) {
                $this->logger->critical($ex->getMessage(), [
                    'code' => $ex->getCode(),
                    'file' => $ex->getFile(),
                    'line' => $ex->getLine(),
                    'exception' => $ex
                ]);
            }
        } else {
            $this->order = null;
        }
        $this->video = $video;
        $this->testId = $testId;
        $this->logger = $logger;
    }

    public function getSuccess(): bool
    {
        return (bool)$this->success;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message
            ? (string)$this->message
            : ($this->getSuccess() ? '' : (string)__('Failed to place order'));
    }

    /**
     * @return float
     */
    public function getRawTotal(): float
    {
        return $this->order
            ? (float)$this->order->getBaseGrandTotal()
            : 0.00;
    }

    /**
     * @return string
     */
    public function getTotal(): string
    {
        return $this->order
            ? (string)$this->priceFormatter->currency($this->order->getBaseGrandTotal(), true, false)
            : '';
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->order
            ? (string)$this->order->getIncrementId()
            : '';
    }

    public function getOrderId(): int
    {
        return $this->order
            ? (int)$this->order->getEntityId()
            : 0;
    }

    public function getTitle(): string
    {
        return (string)$this->title;
    }

    public function getPurchaseType(): string
    {
        return (string)$this->purchaseType;
    }

    public function getTestId(): string
    {
        return (string)$this->testId;
    }

    public function getVideo(): string
    {
        return (string)$this->video;
    }


}
