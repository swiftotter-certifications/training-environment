<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/31/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod\Collection;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface;
use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\LocationPrintMethodRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod\CollectionFactory;
use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier as TierResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintResource;

class LocationPrintMethodRepository implements LocationPrintMethodRepositoryInterface
{
    /** @var LocationPrintMethodResource */
    protected $locationPrintMethodResource;

    /** @var LocationPrintMethodFactory */
    private $locationPrintMethodFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var LocationPrintMethodSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var TierResource  */
    private $tierResource;

    /** @var LocationResource  */
    private $locationResource;

    /** @var PrintResource  */
    private $printMethodResource;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LocationPrintMethodFactory $locationPrintMethodFactory,
        LocationPrintMethodResource $locationPrintMethodResource,
        LocationPrintMethodSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        TierResource $tierResource,
        LocationResource $locationResource,
        PrintResource $printMethodResource,
        LoggerInterface $logger
    ) {
        $this->locationPrintMethodFactory = $locationPrintMethodFactory;
        $this->logger = $logger;
        $this->locationPrintMethodResource = $locationPrintMethodResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->tierResource = $tierResource;
        $this->locationResource = $locationResource;
        $this->printMethodResource = $printMethodResource;
    }

    public function getById(int $id): LocationPrintMethodInterface
    {
        /** @var LocationPrintMethodInterface $locationPrintMethod */
        $locationPrintMethod = $this->locationPrintMethodFactory->create();

        try {
            $this->locationPrintMethodResource->load($locationPrintMethod, (int) $id, 'id');
            if (!$locationPrintMethod->getId()) {
                throw new NoSuchEntityException(__('No Location Print Method Mapping found with the id: %1', $id));
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
        return $locationPrintMethod;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): LocationPrintMethodSearchResultsInterface
    {
        /** @var LocationPrintMethodSearchResultsInterface $searchResults */
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

    public function save(LocationPrintMethodInterface $data, int $id = null): LocationPrintMethodInterface
    {
        try {
            if ($id !== null) {
                $existingId = $this->locationPrintMethodResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->locationPrintMethodResource->save($data);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new CouldNotSaveException(
                __(
                    'Could not save Location Print Method: %1',
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
            /** @var LocationPrintMethodInterface $locationPrintMethod */
            $locationPrintMethod = $this->locationPrintMethodFactory->create();
            $this->locationPrintMethodResource->load($locationPrintMethod, $id, 'id');

            if (!$locationPrintMethod->getId()) {
                throw new NoSuchEntityException(__('Location Print Method was not found.'));
            }
            $this->locationPrintMethodResource->delete($locationPrintMethod);

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
                    'Cannot delete Location Print Method with id %1',
                    $locationPrintMethod->getId()
                ),
                $e
            );
        }

        return true;
    }
}
