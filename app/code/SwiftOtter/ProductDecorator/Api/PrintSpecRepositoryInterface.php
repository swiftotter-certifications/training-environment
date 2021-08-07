<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecSearchResultsInterface;

/**
 * This locates print specifications
 *
 * Interface PrintSpecRepositoryInterface
 * @package SwiftOtter\ProductDecorator\Api
 */
interface PrintSpecRepositoryInterface
{
    /**
     * Get Info about Method By ID
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): PrintSpecInterface;

    /**
     * Get List of Method by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintSpecSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PrintSpecSearchResultsInterface;

    /**
     * Create/Update Method
     * @param \SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(PrintSpecInterface $data, int $id = null): PrintSpecInterface;

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
