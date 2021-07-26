<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SwiftOtter\OrderExport\Model\OrderExportDetails as OrderExportDetailsModel;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;

class Collection extends AbstractCollection
{
    private $aggregations;

    protected function _construct()
    {
        $this->_init(OrderExportDetailsModel::class, OrderExportDetailsResource::class);
    }
}