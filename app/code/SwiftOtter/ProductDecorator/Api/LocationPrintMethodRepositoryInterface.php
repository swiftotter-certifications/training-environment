<?php

declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Api;

use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodSearchResultsInterface;

interface LocationPrintMethodRepositoryInterface
{
    /**
     * Get Info about Location Print Method By ID
     *
     * @param int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): LocationPrintMethodInterface;

    /**
     * Get List of Location Print Method by Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodSearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): LocationPrintMethodSearchResultsInterface;

    /**
     * Create/Update Location Print Method
     *
     * @param \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface $data
     * @param null|int $id
     * @return \SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(LocationPrintMethodInterface $data, int $id = null): LocationPrintMethodInterface;

    /**
     * Delete Location Print Method By ID
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id): bool;
}
