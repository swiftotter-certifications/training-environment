<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Service;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\ProductDecorator\Api\Data\TierInterface;
use SwiftOtter\ProductDecorator\Api\TierRepositoryInterface;

class Tier
{
    /** @var TierRepositoryInterface */
    private $tierRepository;

    /** @var SearchCriteriaBuilder */
    private $criteriaBuilder;

    /** @var SortOrderBuilder */
    private $sortOrderBuilder;

    public function __construct(
        TierRepositoryInterface $tierRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->tierRepository = $tierRepository;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function getTier(string $sku, int $quantity): TierInterface
    {
        $tiers = $this->getTiersForSku($sku);
        $tiers = $this->filterCorrectTiers($tiers, $sku, $quantity);

        return reset($tiers);
    }

    public function getTiersForSku(string $sku): array
    {
        $this->criteriaBuilder->addSortOrder(
            $this->sortOrderBuilder->setField('min_tier')
                ->setAscendingDirection()
                ->create()
        );

        $tiers = $this->tierRepository->getList($this->criteriaBuilder->create())->getItems();

        if (!count($tiers)) {
            throw new NoSuchEntityException(__('%1 did not match any tiers', $sku));
        }

        return $tiers;
    }

    private function filterCorrectTiers(array $tiers, string $sku, int $qty)
    {
        $output = [];
        $first = null;

        $tiers = array_filter($tiers, function(TierInterface $tier) use ($qty) {
            return $tier->getMaxTier() >= $qty;
        });

        foreach ($tiers as $tier) {
            if ($tier->getMinTier() <= $qty) {
                $first = $tier;
            } else {
                $output[] = $tier;
            }
        }

        if (!$first) {
            $items = array_map(function (TierInterface $tier) {
                return $tier->getId();
            }, $tiers);

            throw new NoSuchEntityException(
                __('No tier found for item < %1 in list (%2): %3',
                    $qty,
                    $sku,
                    implode(', ', $items)
                ));
        }

        array_unshift($output, $first);

        return $output;
    }
}
