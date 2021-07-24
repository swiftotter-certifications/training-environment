<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/22/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Action;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;

class LoadOrCreateCustomer
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /** @var CustomerInterfaceFactory */
    private $customerFactory;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var Encryptor */
    private $encryptor;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterfaceFactory $customerFactory,
        StoreManagerInterface $storeManager,
        Encryptor $encryptor
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->encryptor = $encryptor;
    }

    public function execute(string $email, OrderInterface $order): CustomerInterface
    {
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException $exception) {
            $customer = $this->createCustomer($email, $order);
        }

        return $customer;
    }

    private function createCustomer(string $email, OrderInterface $order)
    {
        $customer = $this->customerFactory->create();
        $customer->setEmail($email);
        $customer->setWebsiteId($this->storeManager->getStore($order->getStoreId())->getWebsiteId());
        $customer->setFirstname('TBD');
        $customer->setLastname('TBD');

        $password = 'pass' . rand(1000, 9999);

        $customer = $this->customerRepository->save($customer, $this->encryptor->getHash($password, true));

        $customer->setData('password', $password);

        return $customer;
    }
}