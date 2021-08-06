<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use SwiftOtter\ProductDecorator\Api\Data\TierInterface;
use SwiftOtter\ProductDecorator\Api\Data\TierSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\TierRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier as TierResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier\CollectionFactory;
use SwiftOtter\ProductDecorator\Api\Data\TierSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class TierRepository implements TierRepositoryInterface
{
    /** @var TierResource */
    protected $tierResource;

    /** @var TierFactory */
    private $tierFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var TierSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var SortOrder  */
    private $sortOrder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        TierFactory $tierFactory,
        TierResource $tierResource,
        TierSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SortOrder $sortOrder,
        LoggerInterface $logger
    ) {
        $this->tierFactory = $tierFactory;
        $this->logger = $logger;
        $this->tierResource = $tierResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sortOrder = $sortOrder;
    }

    public function getById(int $id): TierInterface
    {
        try {
            /** @var TierInterface $tier */
            $tier = $this->tierFactory->create();
            $this->tierResource->load($tier, (int) $id, 'id');

            if (!$tier->getId()) {
                throw new NoSuchEntityException(__('No Tier found with the id: %1', $id));
            }

            return $tier;
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
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        try {
            $sortOrder = $this->sortOrder->setField('min_tier')
                ->setDirection(SortOrder::SORT_ASC);

            $searchCriteria->setSortOrders([$sortOrder]);

            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $this->collectionProcessor->process($searchCriteria, $collection);
            $collection->load();

            /** @var TierSearchResultsInterface $searchResults */
            $searchResult = $this->searchResultsFactory->create();
            $searchResult->setSearchCriteria($searchCriteria);
            $searchResult->setItems($collection->getItems());
            $searchResult->setTotalCount($collection->getSize());

            return $searchResult;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function save(TierInterface $data, int $id = null): TierInterface
    {
        try {
            if ($id !== null) {
                $existingId = $this->tierResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->tierResource->save($data);
            return $data;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function deleteById(int $id): bool
    {
        try {
            /** @var TierInterface $quote */
            $tier = $this->tierFactory->create();
            $this->tierResource->load($tier, $id);

            if (!$tier->getId()) {
                throw new NoSuchEntityException(__('Tier was not found.'));
            }

            $this->tierResource->delete($tier);
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

        return true;
    }
}
