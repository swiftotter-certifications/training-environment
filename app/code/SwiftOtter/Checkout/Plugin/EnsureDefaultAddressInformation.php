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
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Quote\Model\QuoteManagement as CartManagement;
use SwiftOtter\Checkout\Model\RegisterPasswordTransport;
use SwiftOtter\Customer\Action\CartCustomerExtractor;
use SwiftOtter\Utils\Model\ResourceModel\CustomerLookup;

class EnsureDefaultAddressInformation
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

    /** @var RegionCollectionFactory */
    private $regionCollectionFactory;

    public function __construct(
        RegisterPasswordTransport $registerPasswordTransport,
        AccountManagementInterface $accountManagement,
        CustomerFactory $customerFactory,
        CustomerLookup $customerLookup,
        CartCustomerExtractor $cartCustomerExtractor,
        RegionCollectionFactory $regionCollectionFactory
    ) {
        $this->registerPasswordTransport = $registerPasswordTransport;
        $this->accountManagement = $accountManagement;
        $this->customerFactory = $customerFactory;
        $this->customerLookup = $customerLookup;
        $this->cartCustomerExtractor = $cartCustomerExtractor;
        $this->regionCollectionFactory = $regionCollectionFactory;
    }

    public function beforeSubmit(CartManagement $subject, CartModel $cart, $orderData = [])
    {
        $this->completeAddress($cart->getBillingAddress());
        if ($cart->isVirtual() && !$cart->getBillingAddress()->getPostcode()) {
            $cart->getBillingAddress()->setPostcode(11111);
        }

        return null;
    }

    private function completeAddress(CartModel\Address $address): void
    {
        if (!$address->getStreetFull()) {
            $address->setStreetFull('Placeholder street');
        }

        if (!$address->getCity()) {
            $address->setCity('Placeholder city');
        }

        $region = $this->regionCollectionFactory->create()
            ->addFieldToFilter('country_id', $address->getCountryId())
            ->getFirstItem();

        if (!$address->getRegionCode()) {
            $address->setRegionCode($region->getCode());
            $address->setRegion($region->getDefaultName());
        }

        if (!$address->getRegionId()) {
            $address->setRegionId($region->getId());
        }

        if (!$address->getPostcode()) {
            $address->setPostcode(11111);
        }

        if (!$address->getTelephone()) {
            $address->setTelephone('111-111-1111');
        }
    }
}
