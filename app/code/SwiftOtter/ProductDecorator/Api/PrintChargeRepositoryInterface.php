<?php

declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintChargeSearchResultsInterface;

/**
 * This stores and returns all values associated with print charges. This is applicable to all pillars.
 *
 * Interface PrintChargeRepositoryInterface
 * @package SwiftOtter\ProductDecorator\Api
 */
interface PrintChargeRepositoryInterface
{
    /**
     * Get Info about Print Charge By ID
     *
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): PrintChargeInterface;

    /**
     * Get List of Print Charges by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintChargeSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PrintChargeSearchResultsInterface;

    /**
     * Create/Update Print Charge
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(PrintChargeInterface $data, int $id = null): PrintChargeInterface;

    /**
     * Delete Print Charge By ID
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id): bool;
}
