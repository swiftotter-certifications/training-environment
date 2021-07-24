<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/25/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Customer\Action;

use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;

class CountryIsValid
{
    /** @var CountryCollectionFactory */
    private $countryCollectionFactory;

    public function __construct(CountryCollectionFactory $countryCollectionFactory)
    {
        $this->countryCollectionFactory = $countryCollectionFactory;
    }

    public function execute(string $countryCode): bool
    {
        $country = $this->countryCollectionFactory->create()
            ->addFieldToFilter('country_id', $countryCode)
            ->getFirstItem();

        return (bool)$country->getCountryId();
    }
}
