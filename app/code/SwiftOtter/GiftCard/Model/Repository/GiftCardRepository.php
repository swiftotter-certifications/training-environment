<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Repository;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data;
use Magento\Cms\Model\ResourceModel\Block as ResourceBlock;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as BlockCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterfaceFactory as GiftCardSearchResultsFactory;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;
use SwiftOtter\GiftCard\Model\EmailNotification;
use SwiftOtter\GiftCard\Model\GiftCardFactory;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard as GiftCardResource;
use SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;

class GiftCardRepository implements GiftCardRepositoryInterface
{
    /** @var GiftCardResource */
    protected $resource;

    /** @var GiftCardFactory */
    protected $giftCardFactory;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var GiftCardSearchResultsFactory */
    protected $searchResultsFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;
    /** @var EmailNotification */
    private $emailNotification;

    public function __construct(
        GiftCardResource $resource,
        GiftCardFactory $giftCardFactory,
        CollectionFactory $collectionFactory,
        GiftCardSearchResultsFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        EmailNotification $emailNotification
    ) {
        $this->resource = $resource;
        $this->giftCardFactory = $giftCardFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->emailNotification = $emailNotification;
    }

    public function save(GiftCardInterface $giftCard, ?int $storeId = 0)
    {
        try {
            $canNotify = !(bool)$giftCard->getId();

            $this->resource->save($giftCard);

            if ($canNotify) {
                $this->emailNotification->send($storeId, $giftCard);
            }
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $giftCard;
    }

    public function getById($cardId)
    {
        $block = $this->giftCardFactory->create();
        $this->resource->load($block, $cardId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The gift card with the "%1" ID doesn\'t exist.', $cardId));
        }
        return $block;
    }

    public function getByCode($cardId)
    {
        $block = $this->giftCardFactory->create();
        $this->resource->load($block, $cardId, 'code');
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('The gift card with the code "%1" ID doesn\'t exist.', $cardId));
        }
        return $block;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var \SwiftOtter\GiftCard\Model\ResourceModel\GiftCard\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var GiftCardSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function delete(GiftCardInterface $block)
    {
        try {
            $this->resource->delete($block);
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
