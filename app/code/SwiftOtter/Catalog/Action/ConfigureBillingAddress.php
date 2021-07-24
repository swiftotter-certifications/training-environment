<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/28/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Action;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Model\ResourceModel\AddressRepository;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use SwiftOtter\Catalog\Api\Data\IncomingOrderDetailsInterface;

class ConfigureBillingAddress
{
    /** @var AddressRepository */
    private $addressRepository;

    /** @var CustomerSession */
    private $customerSession;

    /** @var CustomerRepository */
    private $customerRepository;

    /** @var RegionCollectionFactory */
    private $regionCollectionFactory;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var AddressInterfaceFactory */
    private $addressFactory;

    public function __construct(
        AddressRepository $addressRepository,
        CustomerSession $customerSession,
        CustomerRepository $customerRepository,
        RegionCollectionFactory $regionCollectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AddressInterfaceFactory $addressFactory
    ) {
        $this->addressRepository = $addressRepository;
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->addressFactory = $addressFactory;
    }

    public function execute(QuoteAddress $quoteAddress, IncomingOrderDetailsInterface $orderDetails)
    {
        $postalCode = $orderDetails->getPostalCode() ?: '68131';
        $countryId = 'US';

        $quoteAddress->setFirstname($this->getFirstName($orderDetails))
            ->setLastname($this->getLastName($orderDetails))
            ->setEmail($orderDetails->getEmail());

        $address = $orderDetails->getAddress();

        if ($address && $address->getAddressId()) {
            try {
                $customerAddress = $this->addressRepository->getById($address->getAddressId());
                if ($customerAddress->getCustomerId() != $this->getCustomer($orderDetails)->getId()) {
                    throw new NotFoundException(__('This address was not found.'));
                }

                $quoteAddress->setCompany($customerAddress->getCompany())
                    ->setStreet($customerAddress->getStreet())
                    ->setCity($customerAddress->getCity())
                    ->setRegionCode($customerAddress->getRegion()->getRegionCode())
                    ->setPostcode($customerAddress->getPostcode())
                    ->setCountryId($customerAddress->getCountryId())
                    ->setTelephone($customerAddress->getTelephone());

                return;
            } catch (NoSuchEntityException $ex) {
                // do nothing
            }
        }

        if ($address && $address->getCity()) {
            $quoteAddress->setCompany($address->getCompany());
            $quoteAddress->setStreet($address->getStreet() ?? 'No address specified');
            $quoteAddress->setCity($address->getCity() ?? 'No city');
            if (is_numeric($address->getRegion())) {
                $quoteAddress->setRegionId($address->getRegion() ?? '0');
            } else {
                $quoteAddress->setRegion($address->getRegion() ?? '0');
                $region = $this->regionCollectionFactory->create()
                    ->addRegionNameFilter($quoteAddress->getRegion())
                    ->getFirstItem();

                if ($region->getId()) {
                    $quoteAddress->setRegionId($region->getId());
                    $quoteAddress->setRegionCode($region->getCode());
                }
            }

            $quoteAddress->setPostcode($address->getPostalCode() ?? $postalCode);
            $quoteAddress->setCountryId($address->getCountry() ?? $countryId);
            $quoteAddress->setTelephone($address->getCountry() ?? '222-222-2222');
            $quoteAddress->setVatId($address->getVatId() ?? '');

            $customerAddress = $this->addressFactory->create();
            $customerAddress->setFirstname($this->getFirstName($orderDetails));
            $customerAddress->setLastname($this->getLastName($orderDetails));
            $customerAddress->setCompany($quoteAddress->getCompany());
            $customerAddress->setStreet($quoteAddress->getStreet());
            $customerAddress->setCity($quoteAddress->getCity());
            $customerAddress->setRegionId($quoteAddress->getRegionId());
            $customerAddress->setPostcode($quoteAddress->getPostcode());
            $customerAddress->setCountryId($quoteAddress->getCountryId());
            $customerAddress->setTelephone($quoteAddress->getTelephone());
            $customerAddress->setVatId($quoteAddress->getVatId());

            return;
        }

        if ($customerAddress = $this->getDefaultCustomerAddress($orderDetails)) {
            $quoteAddress->setCompany($customerAddress->getCompany())
                ->setStreet($customerAddress->getStreet())
                ->setCity($customerAddress->getCity())
                ->setRegionCode($customerAddress->getRegion()->getRegionCode())
                ->setPostcode($customerAddress->getPostcode())
                ->setCountryId($customerAddress->getCountryId())
                ->setTelephone($customerAddress->getTelephone());

            return;
        }

        $quoteAddress->setStreet('No address specified')
            ->setCity('No city')
            ->setRegionCode('NE')
            ->setPostcode($postalCode)
            ->setCountryId($countryId)
            ->setTelephone('111-111-1111');

        return;
    }

    private function getFirstName(IncomingOrderDetailsInterface $details)
    {
        $name = explode(' ', $details->getName());
        return reset($name);
    }

    private function getLastName(IncomingOrderDetailsInterface $details)
    {
        $name = explode(' ', $details->getName());
        array_shift($name);
        return implode(' ', $name);
    }

    private function getCustomer(IncomingOrderDetailsInterface $details)
    {
        $customer = $this->customerSession->getCustomer();

        if (!$customer || !$customer->getId()) {
            try {
                $customer = $this->customerRepository->get($details->getEmail());
            } catch (NoSuchEntityException $ex) {
                // do nothing
            }
        }

        return $customer;
    }

    private function getDefaultCustomerAddress(IncomingOrderDetailsInterface $details): ?AddressInterface
    {
        $addressId = $this->getCustomer($details)->getDefaultBilling()
            ?: $this->getCustomer($details)->getDefaultShipping();

        if ($addressId) {
            return $this->addressRepository->getById($addressId);
        }

        $candidates = $this->addressRepository->getList(
            $this->searchCriteriaBuilder->addFilter('parent_id', $this->getCustomer($details)->getId())->create()
        );

        $customerAddress = $candidates->getItems();

        if (count($customerAddress)) {
            return reset($customerAddress);
        }

        return null;
    }
}
