<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Repository;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardUsageSearchResultsInterfaceFactory as GiftCardUsageSearchResultsFactory;
use SwiftOtter\GiftCard\Model\GiftCardUsageFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage as GiftCardUsageResource;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage\CollectionFactory;

class GiftCardUsageRepository
{
    /** @var GiftCardUsageResource */
    protected $resource;

    /** @var GiftCardUsageFactory */
    protected $giftCardUsageFactory;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var GiftCardUsageSearchResultsFactory */
    protected $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    public function __construct(
        GiftCardUsageResource $resource,
        GiftCardUsageFactory $giftCardUsageFactory,
        CollectionFactory $collectionFactory,
        GiftCardUsageSearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->giftCardUsageFactory = $giftCardUsageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(GiftCardUsageInterface $giftCardUsage)
    {
        try {
            $this->resource->save($giftCardUsage);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $giftCardUsage;
    }

    public function getById($usageId)
    {
        $block = $this->giftCardUsageFactory->create();
        $this->resource->load($block, $usageId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The gift card activity with the "%1" ID doesn\'t exist.', $usageId));
        }
        return $block;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var \SwiftOtter\GiftCard\Model\ResourceModel\GiftCardUsage\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var GiftCardUsageSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(GiftCardUsageInterface $giftCardUsage)
    {
        try {
            $this->resource->delete($giftCardUsage);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    public function deleteById($blockId)
    {
        return $this->delete($this->getById($blockId));
    }
}
