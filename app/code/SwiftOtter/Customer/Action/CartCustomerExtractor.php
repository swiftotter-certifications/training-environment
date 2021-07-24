<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Action;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\DataObject\Copy as CopyService;
use Magento\Customer\Api\Data\RegionInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory as AddressFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory as RegionFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory as CustomerFactory;
use Magento\Quote\Api\Data\AddressInterfaceFactory as QuoteAddressFactory;
use Magento\Sales\Model\Order\Address as OrderAddress;

class CartCustomerExtractor
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /** @var CopyService */
    private $objectCopyService;

    /** @var AddressFactory */
    private $addressFactory;

    /** @var RegionFactory */
    private $regionFactory;

    /** @var CustomerFactory */
    private $customerFactory;

    /** @var QuoteAddressFactory */
    private $quoteAddressFactory;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CopyService $objectCopyService,
        AddressFactory $addressFactory,
        RegionFactory $regionFactory,
        CustomerFactory $customerFactory,
        QuoteAddressFactory $quoteAddressFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->objectCopyService = $objectCopyService;
        $this->addressFactory = $addressFactory;
        $this->regionFactory = $regionFactory;
        $this->customerFactory = $customerFactory;
        $this->quoteAddressFactory = $quoteAddressFactory;
    }

    function extract(CartModel $cart): CustomerInterface
    {
        if ($cart->getCustomerId()) {
            return $this->customerRepository->getById($cart->getCustomerId());
        }

        //Prepare customer data from order data if customer doesn't exist yet.
        $customerData = $this->objectCopyService->copyFieldsetToTarget(
            'order_address',
            'to_customer',
            $cart->getBillingAddress(),
            []
        );

        $processedAddressData = [];
        $customerAddresses = [];
        foreach ($cart->getAllAddresses() as $quoteAddress) {
            $addressData = $this->objectCopyService
                ->copyFieldsetToTarget('order_address', 'to_customer_address', $quoteAddress, []);

            if (empty($addressData['firstname'])) {
                continue;
            }

            $index = array_search($addressData, $processedAddressData);
            if ($index === false) {
                // create new customer address only if it is unique
                $customerAddress = $this->addressFactory->create(['data' => $addressData]);
                $customerAddress->setIsDefaultBilling(false);
                $customerAddress->setIsDefaultShipping(false);
                if (is_string($quoteAddress->getRegion())) {
                    /** @var RegionInterface $region */
                    $region = $this->regionFactory->create();
                    $region->setRegion($quoteAddress->getRegion());
                    $region->setRegionCode($quoteAddress->getRegionCode());
                    $region->setRegionId($quoteAddress->getRegionId());
                    $customerAddress->setRegion($region);
                }

                $processedAddressData[] = $addressData;
                $customerAddresses[] = $customerAddress;
                $index = count($processedAddressData) - 1;
            }

            $customerAddress = $customerAddresses[$index];
            // make sure that address type flags from equal addresses are stored in one resulted address
            if ($quoteAddress->getAddressType() == OrderAddress::TYPE_BILLING) {
                $customerAddress->setIsDefaultBilling(true);
            }
            if ($quoteAddress->getAddressType() == OrderAddress::TYPE_SHIPPING) {
                $customerAddress->setIsDefaultShipping(true);
            }
        }

        $customerData['addresses'] = $customerAddresses;

        return $this->customerFactory->create(['data' => $customerData]);
    }
}
