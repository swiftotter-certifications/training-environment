<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Action;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderRepository;
use SwiftOtter\DownloadProduct\Model\IncomingOrderTransport;
use SwiftOtter\SendAnEmail\Model\DetailsFactory;
use SwiftOtter\SendAnEmail\SendNotification;

class NotifySharedOrder
{
    const NOTIFICATION_EMAIL_PATH = 'catalog/frontend_purchase/share_notification_email';

    /** @var IncomingOrderTransport */
    private $incomingOrderTransport;

    /** @var DetailsFactory */
    private $detailsFactory;

    /** @var SendNotification */
    private $sendNotification;

    /** @var UrlInterface */
    private $url;

    /** @var OrderRepository */
    private $orderRepository;

    public function __construct(
        IncomingOrderTransport $incomingOrderTransport,
        DetailsFactory $detailsFactory,
        SendNotification $sendNotification,
        UrlInterface $url,
        OrderRepository $orderRepository
    ) {
        $this->incomingOrderTransport = $incomingOrderTransport;
        $this->detailsFactory = $detailsFactory;
        $this->sendNotification = $sendNotification;
        $this->url = $url;
        $this->orderRepository = $orderRepository;
    }

    public function execute(CustomerInterface $customer, OrderInterface $order)
    {
        if (!$this->incomingOrderTransport->get()
            || !$this->incomingOrderTransport->get()->getShare()
            || !$this->incomingOrderTransport->get()->getShare()->getEnabled()) {
            return;
        }

        $url = $this->url->getUrl('customer/account');
        $email = $this->incomingOrderTransport->get()->getShare()->getEmail();

        $password = '';
        $customerData = (array)$customer->__toArray();
        if (isset($customerData['password'])) {
            $passwordValue = (array)$customer->__toArray()['password'];
            $password = reset($passwordValue);
        }

        $details = $this->detailsFactory->create(
            $email,
            "You've got some study materials!",
            ""
        );
        $details->addVar('sender_name', $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname());
        $details->addVar('email', $email);
        $details->addVar('password', $password);
        $details->addVar('url', $url);

        try {
            $this->sendNotification->send($details, self::NOTIFICATION_EMAIL_PATH);
        } catch (\Exception $e) {
            mail(
                "joseph@swiftotter.com",
                "Notification is broken",
                $e->getMessage() . "\n" . $e->getTraceAsString()
            );
        }
    }
}
