<?php

declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\TierInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\TierSearchResultsInterface;

/**
 * Tiers determine prices.
 *
 * Interface TierRepositoryInterface
 * @package SwiftOtter\ProductDecorator\Api
 */
interface TierRepositoryInterface
{
    /**
     * Get Info about Tier By ID
     *
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\TierInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): TierInterface;

    /**
     * Get List of Tiers by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\TierSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Create/Update Tier
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\TierInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\TierInterface
     */
    public function save(TierInterface $data, int $id = null): TierInterface;

    /**
     * Delete Tier By ID
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id): bool;
}
