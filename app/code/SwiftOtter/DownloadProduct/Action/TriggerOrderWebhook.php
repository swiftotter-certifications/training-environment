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
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Model\Order\Item;
use Psr\Log\LoggerInterface;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;

class TriggerOrderWebhook
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
    public function execute(OrderInterface $order, IncomingOrderDetailsInterface $orderDetails)
    {
        $json = [
            'timeout' => 2,
            'json' => [
                'order_id' => $order->getIncrementId(),
                'email' => $this->getEmail($order, $orderDetails),
                'name' => $this->getName($order, $orderDetails),
                'items' => $this->formulateItems($order)
            ]
        ];

        try {
            $client = new Client();
            $client->post('https://hooks.zapier.com/hooks/catch/545424/ofriq39/', $json);
        } catch (\Exception $ex) {
            $error = [
                'Error: ' . $ex->getMessage(),
                'Trace: ',
                $ex->getTraceAsString(),
                'Email: ' . $orderDetails->getEmail(),
                'Name: ' . $orderDetails->getName(),
                'ProductIdentifer: ' . $orderDetails->getProductIdentifier(),
                'Json: ' . json_encode($json)
            ];
            $this->logger->critical($ex->getMessage(), $error);
            mail('joseph@swiftotter.com', 'Webhook Error', implode("\n", $error), 'From: Joseph Maxwell <joseph@swiftotter.com>');
        }
    }

    private function formulateItems(OrderInterface $order)
    {
        $items = $this->orderItemRepository->getList(
            $this->searchCriteriaBuilder->addFilter('order_id', $order->getId())->create()
        );

        return array_values(array_map(function(Item $item) {
            return $item->getSku();
        }, $items->getItems()));
    }

    /**
     * @param OrderInterface $order
     * @param IncomingOrderDetailsInterface $orderDetails
     */
    private function getEmail(OrderInterface $order, IncomingOrderDetailsInterface $orderDetails): string
    {
        $email = $order->getCustomerEmail();
        if ($this->isShared($orderDetails)) {
            $email = $orderDetails->getShare()->getEmail();
        }

        return $email;
    }

    /**
     * @param IncomingOrderDetailsInterface $orderDetails
     * @return bool
     */
    private function isShared(IncomingOrderDetailsInterface $orderDetails): bool
    {
        return $orderDetails->getShare()
            && $orderDetails->getShare()->getEnabled()
            && $orderDetails->getShare()->getEmail();
    }

    /**
     * @param OrderInterface $order
     * @param IncomingOrderDetailsInterface $orderDetails
     * @return string
     */
    private function getName(OrderInterface $order, IncomingOrderDetailsInterface $orderDetails): string
    {
        $name = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        if ($this->isShared($orderDetails)) {
            $name = 'Name TBD (' . $orderDetails->getShare()->getEmail() . ')';
        }
        return $name;
    }
}