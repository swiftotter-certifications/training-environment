<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Action;

use GuzzleHttp\Client;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order\Item as OrderItem;
use Psr\Log\LoggerInterface;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;

class TriggerOrderItemWebhook
{
    /** @var OrderItemRepositoryInterface */
    private $orderItemRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
    }

    /**
     * Data sample:
     * {
    "order_id":"{{ item.increment_id }}",
    "email":"{{ item.customer_email }}",
    "name":"{{ item.customer_firstname }} {{ item.customer_lastname }}",
    "items":[{% for row in item.items %}"{{ row.sku }}",{% endfor %}""]
    }
     *
     * @param OrderInterface $order
     * @param IncomingOrderDetailsInterface $orderDetails
     */
    public function execute(OrderInterface $order, OrderItemInterface $orderItem)
    {
        $json = [
            'timeout' => 2,
            'json' => [
                'order_id' => $order->getIncrementId(),
                'email' => $this->getEmail($order, $orderItem),
                'name' => $this->getName($order, $orderItem),
                'items' => $this->formulateItems($orderItem)
            ]
        ];

        try {
            $client = new Client();
            $client->post('https://swiftotter.com/', $json);
        } catch (\Exception $ex) {
            $error = [
                'Error: ' . $ex->getMessage(),
                'Trace: ',
                $ex->getTraceAsString(),
                'Order ID: ' . $order->getIncrementId()
            ];
            $this->logger->critical($ex->getMessage(), $error);
        }
    }

    private function formulateItems(OrderItemInterface $orderItem)
    {
        return [$orderItem->getSku()];
    }

    private function getEmail(OrderInterface $order, OrderItem $orderItem): string
    {
        $email = $order->getCustomerEmail();
        if ($this->isShared($orderItem)) {
            $email = $orderItem->getExtensionAttributes()->getUserInformation()->getEmail();
        }

        return $email;
    }

    private function isShared(OrderItem $orderItem): bool
    {
        return $orderItem->getExtensionAttributes()
            && $orderItem->getExtensionAttributes()->getUserInformation()
            && $orderItem->getExtensionAttributes()->getUserInformation()->getIsShared()
            && $orderItem->getExtensionAttributes()->getUserInformation()->getEmail();
    }

    private function getName(OrderInterface $order, OrderItem $orderItem): string
    {
        $name = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        if ($this->isShared($orderItem)) {
            $name = 'Name TBD (' . $orderItem->getExtensionAttributes()->getUserInformation()->getEmail() . ')';
        }
        return $name;
    }
}
