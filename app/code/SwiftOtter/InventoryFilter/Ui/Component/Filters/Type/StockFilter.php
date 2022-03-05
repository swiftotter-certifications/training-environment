<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/10/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\InventoryFilter\Ui\Component\Filters\Type;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Ui\Component\Filters\FilterModifier;

class StockFilter extends \Magento\Ui\Component\Filters\Type\AbstractFilter
{
    private StockRepositoryInterface $stockRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FilterBuilder $filterBuilder,
        FilterModifier $filterModifier,
        StockRepositoryInterface $stockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $filterBuilder, $filterModifier, $components, $data);
        $this->stockRepository = $stockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function prepare()
    {
        parent::prepare();
        $this->applyFilter();

        $options = $this->getStockOptions();

        $config = $this->getData('config');
        $config['options'] = $options;
        $this->setData('config', $config);
    }

    private function getStockOptions(): array
    {
        $output = [
            ['value' => '', 'label' => __('-- select one --')]
        ];

        $options = $this->stockRepository->getList()->getItems();
        foreach ($options as $option) {
            $output[] = [
                'label' => $option->getName(),
                'value' => $option->getStockId()
            ];
        }

        return $output;
    }

    private function applyFilter()
    {
        if (!isset($this->filterData[$this->getName()])) {
            return;
        }

        $value = $this->filterData[$this->getName()];

        if (empty($value['stock_id'])) {
            return;
        }

        $filter = $this->filterBuilder->setField($this->getName())
            ->setValue($value)
            ->create();

        $this->getContext()->getDataProvider()->addFilter($filter);
    }
}
