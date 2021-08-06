<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintMethodSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintMethodSearchResultsInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\PrintMethodRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

class PrintMethodRepository implements PrintMethodRepositoryInterface
{
    /** @var PrintMethodResource */
    protected $printMethodResource;

    /** @var PrintMethodFactory */
    private $printMethodFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var PrintMethodSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var SortOrder  */
    private $sortOrder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        PrintMethodFactory $printMethodFactory,
        PrintMethodResource $printMethodResource,
        PrintMethodSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SortOrder $sortOrder,
        LoggerInterface $logger
    ) {
        $this->printMethodFactory = $printMethodFactory;
        $this->logger = $logger;
        $this->printMethodResource = $printMethodResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sortOrder = $sortOrder;
    }

    public function getById(int $id): PrintMethodInterface
    {
        /** @var PrintMethodInterface $printmethod */
        $printMethod = $this->printMethodFactory->create();

        try {
            $this->printMethodResource->load($printMethod, (int) $id, 'id');
            if (!$printMethod->getId()) {
                throw new NoSuchEntityException(__('No Print Method found with the id: %1', $id));
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

        return $printMethod;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): PrintMethodSearchResultsInterface
    {
        /** @var PrintMethodSearchResultsInterface $searchResults */
        $searchResult = $this->searchResultsFactory->create();

        try {
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

    public function save(PrintMethodInterface $data, int $id = null): PrintMethodInterface
    {
        try {
            if ($id !== null) {
                $existingId = $this->printMethodResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->printMethodResource->save($data);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new CouldNotSaveException(
                __(
                    'Could not save Print Method: %1',
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
            /** @var PrintMethodInterface $printMethod */
            $printMethod = $this->printMethodFactory->create();
            $this->printMethodResource->load($printMethod, $id);

            if (!$printMethod->getId()) {
                throw new NoSuchEntityException(__('Print Method not found.'));
            }
            $this->printMethodResource->delete($printMethod);
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
                    'Cannot delete print method with id %1',
                    $printMethod->getId()
                ),
                $e
            );
        }

        return true;
    }
}
