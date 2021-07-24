<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Quote\Model\QuoteManagement as CartManagement;
use SwiftOtter\Utils\Model\ResourceModel\CustomerLookup;

class PlaceOrderOnBehalfOfCustomer
{
    /** @var CustomerLookup */
    private $customerLookup;
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    public function __construct(CustomerLookup $customerLookup, CustomerRepositoryInterface $customerRepository)
    {
        $this->customerLookup = $customerLookup;
        $this->customerRepository = $customerRepository;
    }

    public function beforeSubmit(CartManagement $subject, CartModel $cart, $orderData = [])
    {
        if ($cart->getCustomerId()) {
            return null;
        }

        $customerId = $this->customerLookup->locateCustomer($cart->getCustomerEmail());
        if (!$customerId) {
            return null;
        }

        try {
            $customer = $this->customerRepository->getById($customerId);
            $cart->setCheckoutMethod(\Magento\Checkout\Model\Type\Onepage::METHOD_CUSTOMER);
            $cart->setCustomerId($customerId);
            $cart->setCustomerIsGuest(false);
            $cart->setCustomerGroupId($this->customerLookup->getCustomerGroupId($customerId));
            $cart->setCustomer($customer);
        } catch (NoSuchEntityException $ex) {

        }

        return null;
    }
}
