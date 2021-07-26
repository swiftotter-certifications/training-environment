<?php
declare(strict_types=1);

namespace SwiftOtter\GiftCard\ViewModel\Giftcard;

use Magento\Customer\Model\Session;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\GiftCard\Api\GiftCardRepositoryInterface;

class View implements ArgumentInterface
{
    public const FIELD = 'assigned_customer_id';

    /** @var GiftCardRepositoryInterface */
    private $giftCardRepository;

    /** @var SearchCriteria */
    private $searchCriteria;

    /** @var FilterGroup */
    private $filterGroup;

    /** @var Filter */
    private $filter;

    /** @var Session */
    private $session;

    public function __construct(
        SearchCriteria $searchCriteria,
        GiftCardRepositoryInterface $giftCardRepository,
        FilterGroup $filterGroup,
        Filter $filter,
        Session $session
    ) {
        $this->giftCardRepository = $giftCardRepository;
        $this->searchCriteria = $searchCriteria;
        $this->filterGroup = $filterGroup;
        $this->filter = $filter;
        $this->session = $session;
    }

    public function getGiftCards()
    {
        $filter = $this->filter->setField(self::FIELD)
            ->setValue($this->session->getCustomer()->getId());

        $filterGroups = $this->filterGroup->setFilters([$filter]);

        return $this->giftCardRepository->getList($this->searchCriteria->setFilterGroups([$filterGroups]))->getItems();
    }
}
