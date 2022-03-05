<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 1/13/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\InventoryFilter\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\InventorySalesAdminUi\Ui\Component\Listing\Column\SalableQuantity;
use SwiftOtter\InventoryFilter\Model\ResourceModel\StockLookup;

class RebuildStockQuantityLookups
{
    const SOURCE_CODES_KEY = 'source_codes';
    private const ENABLED_SCOPE = 'admin/inventory_filter/fast_stock_lookup';
    private ScopeConfigInterface $scopeConfig;
    private StockLookup $stockLookup;
    private ?array $sourceAssociations = null;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StockLookup $stockLookup
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->stockLookup = $stockLookup;
    }

    /**
     * This replaces the original method's logic. This is slightly concerning, but the original has such an
     * impact on performance that it's worth the tradeoff. Of course, if this isn't enabled, or there are no
     * records, we allow this to proceed unhindered.
     *
     * This is designed to be very performant. We fetch the stock details for the SKUs collectively and filter
     * down where the stock is not "assigned" (through the source). We replace the salable_quantity details.
     *
     * @param SalableQuantity $subject
     * @param callable $proceed
     * @param array $dataSource
     * @return array
     */
    public function aroundPrepareDataSource(SalableQuantity $subject, callable $proceed, array $dataSource): array
    {
        if (!$this->scopeConfig->getValue(self::ENABLED_SCOPE)
            || !$dataSource['data']['totalRecords']) {
            return $proceed(...array_slice(func_get_args(), 2));
        }

        $skus = $this->identifySkus($dataSource['data']['items'] ?? []);
        $stockDetails = $this->stockLookup->getStockDetailsFor($skus);

        foreach ($dataSource['data']['items'] as &$item) {
            $item['salable_quantity'] = $this->assembleSalableQuantity($item['sku'], $stockDetails[$item['sku']] ?? []);
        }

        return $dataSource;
    }

    private function assembleSalableQuantity(?string $sku, array $details): array
    {
        if (!$sku
            || empty($details[self::SOURCE_CODES_KEY])) {
            return [];
        }

        $associations = $this->getSourceAssociations();
        $assignedSources = explode(',', (string)$details[self::SOURCE_CODES_KEY]);
        $allowedStocks = array_filter($associations, function($stockSourceLink) use ($assignedSources) {
            return in_array($stockSourceLink['source_code'], $assignedSources);
        });
        $allowedStocks = array_column($allowedStocks, 'stock_id');

        $output = [];
        foreach ($this->stockLookup->getStockNames() as $stockId => $name) {
            $prefix = "stock_{$stockId}";
            if (!isset($details["{$prefix}_quantity"])
                || !in_array($stockId, $allowedStocks)) {
                continue;
            }

            $output[] = [
                'stock_name' => $name,
                'qty' => round($details["{$prefix}_quantity"]),
                'manage_stock' => $details["{$prefix}_is_salable"]
            ];
        }

        return $output;
    }

    /**
     * @param array{sku: string}[] $items
     * @return string[]
     */
    private function identifySkus(array $items): array
    {
        return array_unique(array_column($items, 'sku'));
    }

    private function getSourceAssociations()
    {
        if ($this->sourceAssociations) {
            return $this->sourceAssociations;
        }

        $this->sourceAssociations = $this->stockLookup->getSourceAssociations();
        return $this->sourceAssociations;
    }
}
