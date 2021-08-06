<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\LocationInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationSearchResultsInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\LocationRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location\Collection;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

class LocationRepository implements LocationRepositoryInterface
{
    /** @var LocationResource */
    protected $locationResource;

    /** @var LocationFactory */
    private $locationFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var LocationSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var SortOrder  */
    private $sortOrder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LocationFactory $locationFactory,
        LocationResource $locationResource,
        LocationSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SortOrder $sortOrder,
        LoggerInterface $logger
    ) {
        $this->locationFactory = $locationFactory;
        $this->logger = $logger;
        $this->locationResource = $locationResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sortOrder = $sortOrder;
    }

    public function getById(int $id): LocationInterface
    {
        /** @var LocationInterface $location */
        $location = $this->locationFactory->create();

        try {
            $this->locationResource->load($location, (int) $id, 'id');
            if (!$location->getId()) {
                throw new NoSuchEntityException(__('No Location found with the id: %1', $id));
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
        return $location;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LocationSearchResultsInterface
    {
        /** @var LocationSearchResultsInterface $searchResults */
        $searchResult = $this->searchResultsFactory->create();

        try {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $this->collectionProcessor->process($searchCriteria, $collection);
            $collection->load();

            $searchResult->setSearchCriteria($searchCriteria);
            $searchResult->setItems($collection->getItems());
            $searchResult->setTotalCount($collection->getSize());
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
        return $searchResult;
    }

    public function save(LocationInterface $data, int $id = null): LocationInterface
    {
        try {
            if ($id !== null) {
                $existingId = $this->locationResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->locationResource->save($data);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new CouldNotSaveException(
                __(
                    'Could not save Location: %1',
                    $e->getMessage()
                ),
                $e
            );
        }

        return $this->getById((int)$data->getId());
    }

    public function deleteById(int $id): bool
    {
        try {
            /** @var LocationInterface $location */
            $location = $this->locationFactory->create();
            $this->locationResource->load($location, $id, 'id');

            if (!$location->getId()) {
                throw new NoSuchEntityException(__('Location was not found.'));
            }
            $this->locationResource->delete($location);
        } catch (NoSuchEntityException $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new StateException(
                __(
                    'Cannot delete Location with id %1',
                    $location->getId()
                ),
                $e
            );
        }

        return true;
    }
}
