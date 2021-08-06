<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintChargeSearchResultsInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintChargeSearchResultsInterfaceFactory;
use SwiftOtter\ProductDecorator\Api\PrintChargeRepositoryInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge as PrintChargeResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge\Collection;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge\CollectionFactory;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier as TierResource;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Psr\Log\LoggerInterface;

class PrintChargeRepository implements PrintChargeRepositoryInterface
{
    /** @var PrintChargeResource */
    protected $printChargeResource;

    /** @var PrintChargeFactory */
    private $printChargeFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface  */
    private $collectionProcessor;

    /** @var PrintChargeSearchResultsInterfaceFactory */
    private $searchResultsFactory;

    /** @var TierResource  */
    private $tierResource;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        PrintChargeFactory $printChargeFactory,
        PrintChargeResource $printChargeResource,
        PrintChargeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        TierResource $tierResource,
        LoggerInterface $logger
    ) {
        $this->printChargeFactory = $printChargeFactory;
        $this->logger = $logger;
        $this->printChargeResource = $printChargeResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->tierResource = $tierResource;
    }

    public function getById(int $id): PrintChargeInterface
    {
        /** @var PrintChargeInterface $printCharge */
        $printCharge = $this->printChargeFactory->create();

        try {
            $this->printChargeResource->load($printCharge, (int) $id, 'id');
            if (!$printCharge->getId()) {
                throw new NoSuchEntityException(__('No Print Charge found with the id: %1', $id));
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
        return $printCharge;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): PrintChargeSearchResultsInterface
    {
        /** @var PrintChargeSearchResultsInterface $searchResults */
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

    public function save(PrintChargeInterface $data, int $id = null): PrintChargeInterface
    {
        if (!$data->getTierId()) {
            throw new InputException(__('There are no Tier for assign to print charge.'));
        }

        $tierId = $this->tierResource->getById($data->getTierId());
        if (!$tierId) {
            throw new NoSuchEntityException(__('No Tier found with the id: %1', $tierId));
        }

        try {
            if ($id !== null) {
                $existingId = $this->printChargeResource->getById($id);
                if ($existingId && $existingId > 0) {
                    $data->setId($existingId);
                }
            }

            $this->printChargeResource->save($data);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw new CouldNotSaveException(
                __(
                    'Could not save Print charge: %1',
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
            /** @var PrintChargeInterface $printCharge */
            $printCharge = $this->printChargeFactory->create();
            $this->printChargeResource->load($printCharge, $id, 'id');

            if (!$printCharge->getId()) {
                throw new NoSuchEntityException(__('Print Charge was not found.'));
            }
            $this->printChargeResource->delete($printCharge);
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
                    'Cannot delete print charge with id %1',
                    $printCharge->getId()
                ),
                $e
            );
        }

        return true;
    }
}
