<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/7/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Plugin;

use Magento\Quote\Model\Quote\Address as QuoteAddress;
use SwiftOtter\Customer\Service\CustomerCountry;

class EnsureCountryIsSet
{
    /** @var CustomerCountry */
    private $customerCountry;

    public function __construct(CustomerCountry $customerCountry)
    {
        $this->customerCountry = $customerCountry;
    }

    public function afterGetCountryId(QuoteAddress $subject, $value)
    {
        return $value ?: $this->customerCountry->get();
    }
}
