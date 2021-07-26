<?php
declare(strict_types=1);

namespace SwiftOtter\GiftCard\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardInterface;
use SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface;

interface GiftCardRepositoryInterface
{
    /**
     * @param GiftCardInterface $giftCard
     * @param int|null $storeId
     * @return void
     */
    public function save(GiftCardInterface $giftCard, ?int $storeId = 0);

    /**
     * @param $cardId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function getById($cardId);

    /**
     * @param $cardId
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardInterface
     */
    public function getByCode($cardId);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \SwiftOtter\GiftCard\Api\Data\GiftCardSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param GiftCardInterface $block
     * @return void
     */
    public function delete(GiftCardInterface $block);

    /**
     * @param $blockId
     * @return void
     */
    public function deleteById($blockId);
}
