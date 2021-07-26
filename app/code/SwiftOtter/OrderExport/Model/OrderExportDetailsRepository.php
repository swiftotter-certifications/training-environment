<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 12/31/19
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterface;
use SwiftOtter\OrderExport\Api\Data\OrderExportDetailsSearchResultsInterfaceFactory as SearchResultsFactory;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails as OrderExportDetailsResource;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails\Collection as OrderExportDetailsCollection;
use SwiftOtter\OrderExport\Model\ResourceModel\OrderExportDetails\CollectionFactory as DetailsCollectionFactory;

class OrderExportDetailsRepository
{
    /**
     * @var OrderExportDetailsResource
     */
    private $resource;

    /**
     * @var OrderExportDetailsFactory
     */
    private $detailsFactory;

    /**
     * @var DetailsCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        OrderExportDetailsResource $resource,
        OrderExportDetailsFactory $detailsFactory,
        DetailsCollectionFactory $collectionFactory,
        SearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->detailsFactory = $detailsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(OrderExportDetails $exportDetails)
    {
        try {
            $this->resource->save($exportDetails);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $exportDetails;
    }

    public function getById($detailsId)
    {
        $details = $this->detailsFactory->create();
        $this->resource->load($details, $detailsId);

        if (!$details->getId()) {
            throw new NoSuchEntityException(__('The order details associated with the "%1" ID doesn\'t exist.', $detailsId));
        }
        return $details;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var OrderExportDetailsCollection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var OrderExportDetailsSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(OrderExportDetails $exportDetails)
    {
        try {
            $this->resource->delete($exportDetails);
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