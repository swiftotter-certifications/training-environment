<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/9/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Customer\Model\ResourceModel\AddressRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Model\Quote as QuoteModel;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\Ui\VaultConfigProvider;
use Psr\Log\LoggerInterface;
use SwiftOtter\Catalog\Action\ConfigureBillingAddress;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;
use SwiftOtter\Catalog\Model\PriceCalculatorFactory;
use SwiftOtter\DownloadProduct\Action\AddProductToQuote;
use SwiftOtter\DownloadProduct\Action\TriggerOrderWebhook;
use SwiftOtter\DownloadProduct\Api\Data\OrderUserInformationInterface;
use SwiftOtter\DownloadProduct\Api\PurchaseDetailsInterface;
use SwiftOtter\DownloadProduct\Attributes;

class Purchase
{
    /** @var \Magento\Quote\Api\CartManagementInterface */
    private $cartManagement;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    private $storeManager;

    /** @var \Magento\Customer\Model\Session */
    private $customerSession;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface */
    private $customerRepository;

    /** @var \Magento\Quote\Api\CartRepositoryInterface */
    private $cartRepositoryInterface;

    /** @var \Magento\Catalog\Model\ProductRepository */
    private $productRepository;

    /** @var Purchase\DetailsFactory */
    private $detailsFactory;

    /** @var \Magento\Sales\Api\OrderRepositoryInterface */
    private $orderRepository;

    /** @var IncomingOrderDetailsInterface */
    private $order;

    /** @var ConfigureBillingAddress */
    private $configureBillingAddress;

    /** @var PriceCalculatorFactory */
    private $priceCalculatorFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var IncomingOrderTransport */
    private $incomingOrderTransport;

    /** @var TriggerOrderWebhook */
    private $triggerOrderWebhook;

    /** @var AddProductToQuote */
    private $addProductToQuote;

    /** @var */
    private $currency;
    /** @var AddressRepository */
    private $customerAddressRepository;

    public function __construct(
        \Magento\Quote\Api\CartManagementInterface $cartManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \SwiftOtter\DownloadProduct\Model\Purchase\DetailsFactory $detailsFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        IncomingOrderDetailsInterface $order,
        ConfigureBillingAddress $configureBillingAddress,
        PriceCalculatorFactory $priceCalculatorFactory,
        LoggerInterface $logger,
        IncomingOrderTransport $incomingOrderTransport,
        TriggerOrderWebhook $triggerOrderWebhook,
        AddProductToQuote $addProductToQuote,
        AddressRepository $customerAddressRepository
    ) {
        $this->cartManagement = $cartManagement;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->orderRepository = $orderRepository;

        $this->productRepository = $productRepository;
        $this->detailsFactory = $detailsFactory;
        $this->order = $order;
        $this->configureBillingAddress = $configureBillingAddress;
        $this->priceCalculatorFactory = $priceCalculatorFactory;
        $this->logger = $logger;
        $this->incomingOrderTransport = $incomingOrderTransport;
        $this->triggerOrderWebhook = $triggerOrderWebhook;
        $this->addProductToQuote = $addProductToQuote;

        $this->currency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $this->customerAddressRepository = $customerAddressRepository;
    }

    public function placeOrder(): PurchaseDetailsInterface
    {
        $orderId = '';
        $success = true;

        try {
            $this->incomingOrderTransport->set($this->order);
            $orderId = $this->cartManagement->placeOrder($this->configureQuote()->getId());

            $order = $this->orderRepository->get((int)$orderId);
            $this->saveShareDetails($order);
            $this->orderRepository->save($order);

            $this->triggerOrderWebhook->execute($order, $this->order);

            $title = __('Success! Your purchase is now available.');
            $message = $this->getMessage();
            $purchaseType = $this->getPurchaseType();
            $video = $this->getVideo();
            $testId = $this->getTestId();
        } catch (\Exception $exception) {
            $error = [
                'Error: ' . $exception->getMessage(),
                'Trace: ',
                $exception->getTraceAsString(),
                'Email: ' . $this->order->getEmail(),
                'Name: ' . $this->order->getName(),
                'ProductIdentifer: ' . $this->order->getProductIdentifier(),
            ];
            $this->logger->critical($exception->getMessage(), $error);
            mail('joseph@swiftotter.com', 'Error', implode("\n", $error), 'From: Joseph Maxwell <joseph@swiftotter.com>');
            $success = false;
            $message = $exception->getMessage();
            $title = __('There was a problem with your order.');
            $purchaseType = 'error';
            $video = '';
            $testId = '';
        }

        return $this->detailsFactory->create([
            'success' => $success,
            'orderId' => $orderId,
            'title' => $title,
            'message' => $message,
            'purchaseType' => $purchaseType,
            'video' => $video,
            'testId' => $testId
        ]);
    }

    private function saveShareDetails(OrderInterface $order)
    {
        foreach ($order->getItems() as $orderItem) {
            /** @var OrderUserInformationInterface $information */
            $information = $orderItem->getExtensionAttributes()->getUserInformation();

            if ($this->order->getShare() && $this->order->getShare()->getEnabled()) {
                $information->setName($this->order->getShare()->getEmail());
                $information->setEmail($this->order->getShare()->getEmail());
                $information->setIsShared(true);
            } else {
                $information->setName($order->getCustomerFirstname() . ' ' . $order->getCustomerLastname());
                $information->setEmail($order->getCustomerEmail());
            }

            $information->setOrderId((int)$order->getId());
        }
    }

    private function getTestId()
    {
        $product = $this->getProduct();

        if ($product->getData('test_id')) {
            return $product->getData('test_id');
        }

        if (!$product->getCustomAttribute('test_id')
            || !$product->getCustomAttribute('test_id')->getValue()) {
            return;
        }

        return $product->getCustomAttribute('test_id')->getValue();
    }

    private function getVideo()
    {
        $product = $this->getProduct();

        if (!$product->getCustomAttribute(Attributes::POST_PURCHASE_VIDEO)
            || !$product->getCustomAttribute(Attributes::POST_PURCHASE_VIDEO)->getValue()) {
            return;
        }

        return $product->getCustomAttribute(Attributes::POST_PURCHASE_VIDEO)->getValue();
    }

    private function getMessage()
    {
        $product = $this->getProduct();

        if (!$product->getCustomAttribute(Attributes::POST_PURCHASE_MESSAGE)
            || !$product->getCustomAttribute(Attributes::POST_PURCHASE_MESSAGE)->getValue()) {
            return;
        }

        return $product->getCustomAttribute(Attributes::POST_PURCHASE_MESSAGE)->getValue();
    }

    private function getPurchaseType()
    {
        $product = $this->getProduct();

        if (!$product->getCustomAttribute(Attributes::PURCHASE_TYPE)
            || !$product->getCustomAttribute(Attributes::PURCHASE_TYPE)->getValue()) {
            return;
        }

        return $product->getCustomAttribute(Attributes::PURCHASE_TYPE)->getValue();
    }

    private function configureQuote(): \Magento\Quote\Api\Data\CartInterface
    {
        if (!$this->getCustomer() || !$this->getCustomer()->getId()) {
            $cartId = $this->cartManagement->createEmptyCart();
        } else {
            $cartId = $this->cartManagement->createEmptyCartForCustomer((int)$this->getCustomer()->getId());
        }

        /** @var QuoteModel $quote */
        $quote = $this->createCleanQuoteFor($cartId);
        $quote->setBaseCurrencyCode('USD');
        $quote->setQuoteCurrencyCode('USD');

        $this->addItem($quote);

        if ($this->order->getPayload()->getType() === 'PayPalAccount') {
            $quote->setData('payment_method', 'braintree_paypal');
        } else {
            $quote->setData('payment_method', \PayPal\Braintree\Model\Ui\ConfigProvider::CODE);
        }
        $quote->setData('inventory_processed', false);

        $quote->setCustomerEmail($this->order->getEmail() ?? '');
        $quote->setCustomerFirstname($this->getFirstName());
        $quote->setCustomerLastname($this->getLastName());
        $quote->setCouponCode($this->order->getDiscount());

        $this->configureBillingAddress->execute($quote->getBillingAddress(), $this->order);

        $quote->getPayment()->importData($this->getPaymentData());
        $quote->setTotalsCollectedFlag(false);

        $quote->collectTotals();
        $quote->save();

        return $quote;
    }

    private function createCleanQuoteFor($cartId)
    {
        /** @var QuoteModel $quote */
        $quote = $this->cartRepositoryInterface->get($cartId);
        $quote->setBaseCurrencyCode('USD');
        $quote->setQuoteCurrencyCode('USD'); //$this->order->getCurrency() ?? 'USD');

        $quote->setStoreId($this->storeManager->getStore()->getId());
        $quote->setCheckoutMethod($this->getCheckoutMethod());
        $this->cleanQuoteAddresses($quote);

        $quote->removeAllItems();
        $quote->save();
        $quote->getItemsCollection()->clear();
        $quote->save();

        return $quote;
    }

    private function getCustomer()
    {
        $customer = $this->customerSession->getCustomer();

        if (!$customer || !$customer->getId()) {
            try {
                $customer = $this->customerRepository->get($this->order->getEmail());
            } catch (NoSuchEntityException $ex) {
                mail("joseph@swiftotter.com", "Can't find user", $this->order->getEmail() . "\n" . $ex->getMessage());
            }
        }

        return $customer;
    }

    private function getPaymentData(): array
    {
        if ($this->order->getPayload()->getHash()) {
            $output = [
                PaymentTokenInterface::CUSTOMER_ID => $this->getCustomer()->getId(),
                PaymentTokenInterface::PUBLIC_HASH => $this->order->getPayload()->getHash(),
                VaultConfigProvider::IS_ACTIVE_CODE => true,
                'nonce' => $this->order->getPayload()->getNonce(),
                'payment_method_nonce' => $this->order->getPayload()->getNonce()
            ];
            $output[PaymentInterface::KEY_METHOD] = \Paypal\Braintree\Model\Ui\ConfigProvider::CC_VAULT_CODE;

            return $output;
        } else {
            $output = [
                PaymentInterface::KEY_ADDITIONAL_DATA => [
                    'nonce' => $this->order->getPayload()->getNonce(),
                    'payment_method_nonce' => $this->order->getPayload()->getNonce(),
                    'payment_type' => $this->order->getPayload()->getType(),
                    'description' => $this->order->getPayload()->getDescription(),
                    'cc_last_4' => $this->order->getPayload()->getLastFour(),
                    'cc_type' => $this->order->getPayload()->getCardType(),
                ]
            ];
        }

        if ($this->order->getPayload()->getType() === 'PayPalAccount') {
            $output[PaymentInterface::KEY_METHOD] = 'braintree_paypal';
        } else {
            $output[PaymentInterface::KEY_METHOD] = \Paypal\Braintree\Model\Ui\ConfigProvider::CODE;
        }

        return $output;
    }

    private function getEmail()
    {
        $email = $this->order->getEmail();

        if (!$email && $this->getCustomer()) {
            $email = $this->getCustomer()->getEmail();
        }

        return $email;
    }

    private function getProduct()
    {
        $identifier = $this->order->getProductIdentifier();
        return $this->productRepository->get($identifier);
    }

    private function getCheckoutMethod()
    {
        if ($this->getCustomer() && $this->getCustomer()->getId()) {
            return 'customer';
        } else {
            return \Magento\Quote\Api\CartManagementInterface::METHOD_GUEST;
        }
    }

    private function getFirstName()
    {
        $name = explode(' ', $this->order->getName());
        return reset($name);
    }

    private function getLastName()
    {
        $name = explode(' ', $this->order->getName());
        array_shift($name);
        return implode(' ', $name);
    }

    public function addItem(QuoteModel $quote): void
    {
        $this->addProductToQuote->execute($quote, $this->order, $this->getProduct());
    }

    private function cleanQuoteAddresses(QuoteModel $quote): void
    {
        /** @var QuoteAddress $address */
        foreach ($quote->getAddressesCollection() as $address) {
            if (!$address->getCustomerAddressId()) {
                continue;
            }

            try {
                $this->customerAddressRepository->getById($address->getCustomerAddressId());
            } catch (NoSuchEntityException $ex) {
                $address->setCustomerId(null);
            }
        }
    }
}
