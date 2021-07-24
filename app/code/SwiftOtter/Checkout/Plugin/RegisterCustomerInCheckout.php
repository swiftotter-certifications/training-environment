<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory as CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Quote\Model\QuoteManagement as CartManagement;
use SwiftOtter\Checkout\Model\RegisterPasswordTransport;
use SwiftOtter\Checkout\Service\IsRegisterRequired;
use SwiftOtter\Customer\Action\CartCustomerExtractor;
use SwiftOtter\Utils\Model\ResourceModel\CustomerLookup;

class RegisterCustomerInCheckout
{
    /** @var RegisterPasswordTransport */
    private $registerPasswordTransport;

    /** @var AccountManagementInterface */
    private $accountManagement;

    /** @var CustomerFactory */
    private $customerFactory;

    /** @var CustomerLookup */
    private $customerLookup;

    /** @var CartCustomerExtractor */
    private $cartCustomerExtractor;

    /** @var CustomerSession */
    private $customerSession;
    /** @var CustomerCollectionFactory */
    private $collectionFactory;
    /** @var IsRegisterRequired */
    private $isRegisterRequired;

    public function __construct(
        RegisterPasswordTransport $registerPasswordTransport,
        AccountManagementInterface $accountManagement,
        CustomerFactory $customerFactory,
        CustomerLookup $customerLookup,
        CartCustomerExtractor $cartCustomerExtractor,
        CustomerSession $customerSession,
        CustomerCollectionFactory $collectionFactory,
        IsRegisterRequired $isRegisterRequired
    ) {
        $this->registerPasswordTransport = $registerPasswordTransport;
        $this->accountManagement = $accountManagement;
        $this->customerFactory = $customerFactory;
        $this->customerLookup = $customerLookup;
        $this->cartCustomerExtractor = $cartCustomerExtractor;
        $this->customerSession = $customerSession;
        $this->collectionFactory = $collectionFactory;
        $this->isRegisterRequired = $isRegisterRequired;
    }

    public function beforeSubmit(CartManagement $subject, CartModel $cart, $orderData = [])
    {
        if (!$this->registerPasswordTransport->getPassword()
            && $this->isRegisterRequired->get()
            && !$this->customerSession->getCustomerId()) {
            throw new LocalizedException(__(
                'You must specify a password. Otherwise, ' .
                'you can\'t get access to your downloads. Be glad we are Magento experts and ' .
                'have modified this checkout. Otherwise, you would have had to register before ' .
                'even starting this process!'));
        }

        if (!$this->registerPasswordTransport->getPassword()
            || $this->customerLookup->locateCustomer($cart->getCustomerEmail())) {
            return null;
        }

        $customer = $this->cartCustomerExtractor->extract($cart);

        $customer = $this->accountManagement->createAccount(
            $customer,
            $this->registerPasswordTransport->getPassword()
        );

        $cart->setCheckoutMethod(\Magento\Checkout\Model\Type\Onepage::METHOD_CUSTOMER);
        $cart->setCustomerId($customer->getId());
        $cart->setCustomerIsGuest(false);
        $cart->setCustomerGroupId($customer->getGroupId());
        $cart->setCustomer($customer);

        $this->customerSession->setCustomerAsLoggedIn(
            $this->collectionFactory->create()
                ->addFieldToFilter('entity_id', $customer->getId())
                ->getFirstItem()
        );

        $this->customerSession->regenerateId();

        return null;
    }
}
