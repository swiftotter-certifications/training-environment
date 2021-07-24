<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/26/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Service;

use Magento\Tax\Model\ResourceModel\Calculation\Rate\CollectionFactory as TaxRateCollectionFactory;

class Tax
{
    /** @var TaxRateCollectionFactory */
    private $taxRateCollectionFactory;

    public function __construct(TaxRateCollectionFactory $taxRateCollectionFactory)
    {
        $this->taxRateCollectionFactory = $taxRateCollectionFactory;
    }

    public function getForCountry($country)
    {
        if (!$country) {
            return 0.0;
        }

        /** @var \Magento\Tax\Model\ResourceModel\Calculation\Rate\Collection $collection */
        $collection = $this->taxRateCollectionFactory->create();
        $collection->addFieldToFilter('tax_country_id', $country);

        /** @var \Magento\Tax\Model\Calculation\Rate $rate */
        $rate = $collection->getFirstItem();
        if (!$rate->getId()) {
            return 0.0;
        }

        return $rate->getRate() / 100;
    }
}
