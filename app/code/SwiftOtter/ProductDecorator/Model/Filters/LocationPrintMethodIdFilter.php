<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc.
 **/

namespace SwiftOtter\ProductDecorator\Model\Filters;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod;

class LocationPrintMethodIdFilter implements CustomFilterInterface
{
    public function apply(Filter $filter, AbstractDb $collection)
    {
        $collection->getSelect()->joinInner(
            ['location_print_method' => LocationPrintMethod::TABLE],
            'location_print_method.location_id = main_table.id',
            []
        );

        $collection->getSelect()->where(
            'location_print_method.print_method_id = ?',
            $filter->getValue()
        );

        return true;
    }

}
