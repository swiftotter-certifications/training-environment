<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\LocationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationSearchResultsInterface;

/**
 * This configures locations (sides are the counterpart, and connected through side locations).
 *
 * Interface LocationRepositoryInterface
 * @package SwiftOtter\ProductDecorator\Api
 */
interface LocationRepositoryInterface
{
    /**
     * Get Info about Location By ID
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): LocationInterface;

    /**
     * Get List of Location by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): LocationSearchResultsInterface;

    /**
     * Create/Update Location
     * @param \SwiftOtter\ProductDecorator\Api\Data\LocationInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(LocationInterface $data, int $id = null): LocationInterface;

    /**
     * Delete Location By ID
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id): bool;
}
