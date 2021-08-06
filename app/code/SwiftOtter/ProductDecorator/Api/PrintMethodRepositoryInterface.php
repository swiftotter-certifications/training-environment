<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintMethodSearchResultsInterface;

/**
 * This identifies available print methods. Of particular note is the price type which drives pricing lookups.
 *
 * Interface PrintMethodRepositoryInterface
 * @package SwiftOtter\ProductDecorator\Api
 */
interface PrintMethodRepositoryInterface
{
    /**
     * Get Info about Method By ID
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): PrintMethodInterface;

    /**
     * Get List of Method by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintMethodSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PrintMethodSearchResultsInterface;

    /**
     * Create/Update Method
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(PrintMethodInterface $data, int $id = null): PrintMethodInterface;

    /**
     * Delete Method By ID
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id): bool;
}
