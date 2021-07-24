<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 3/19/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Plugin\Customer;


class SectionData
{
    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    private $currentCustomer;

    public function __construct(
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
    ) {

        $this->currentCustomer = $currentCustomer;
    }

    public function afterGetSectionData(\Magento\Customer\CustomerData\Customer $context, $customerData)
    {
        if (isset($customerData['fullname'])) {
            $customerData['email'] = $this->currentCustomer->getCustomer()->getEmail();
        }

        return $customerData;
    }
}