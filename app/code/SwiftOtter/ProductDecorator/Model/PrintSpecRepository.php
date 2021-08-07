<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecSearchResultsInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\PrintSpecRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

class PrintSpecRepository implements PrintSpecRepositoryInterface
{
    /** @var PrintSpecResource */
    protected $printSpecResource;

    /** @var PrintSpecFactory */
    private $printSpecFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var PrintSpecSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var SortOrder  */
    private $sortOrder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        PrintSpecFactory $printSpecFactory,
        PrintSpecResource $printSpecResource,
        PrintSpecSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SortOrder $sortOrder,
        LoggerInterface $logger
    ) {
        $this->printSpecFactory = $printSpecFactory;
        $this->logger = $logger;
        $this->printSpecResource = $printSpecResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sortOrder = $sortOrder;
    }

    public function getById(int $id): PrintSpecInterface
    {
        /** @var PrintSpecInterface $printSpec */
        $printSpec = $this->printSpecFactory->create();

        try {
            $this->printSpecResource->load($printSpec, (int) $id, 'id');
            if (!$printSpec->getId()) {
                throw new NoSuchEntityException(__('No Print Spec found with the id: %1', $id));
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

        return $printSpec;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): PrintSpecSearchResultsInterface
    {
        /** @var PrintSpecSearchResultsInterface $searchResults */
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

    public function save(PrintSpecInterface $data, int $id = null): PrintSpecInterface
    {
        try {
            if ($id !== null) {
                $existingId = $this->printSpecResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->printSpecResource->save($data);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new CouldNotSaveException(
                __(
                    'Could not save Print spec: %1',
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
            /** @var PrintSpecInterface $printSpec */
            $printSpec = $this->printSpecFactory->create();
            $this->printSpecResource->load($printSpec, $id);

            if (!$printSpec->getId()) {
                throw new NoSuchEntityException(__('Print spec not found.'));
            }
            $this->printSpecResource->delete($printSpec);
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
                    'Cannot delete print spec with id %1',
                    $printSpec->getId()
                ),
                $e
            );
        }

        return true;
    }
}
