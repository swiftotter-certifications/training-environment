<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Action;

use Magento\Catalog\Model\Indexer\Product\Price\Processor as PriceProcessor;
use Psr\Log\LoggerInterface;
use SwiftOtter\ProductDecorator\Attributes;
use SwiftOtter\Utils\Model\ResourceModel\ProductLookup;

class UpdateDefaultPrice
{
    /** @var ProductLookup */
    private $productLookup;

    /** @var FetchDefaultPrice */
    private $fetchDefaultPrice;

    /** @var PriceProcessor */
    private $priceProcessor;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        ProductLookup $productLookup,
        FetchDefaultPrice $fetchDefaultPrice,
        PriceProcessor $priceProcessor,
        LoggerInterface $logger
    ) {
        $this->productLookup = $productLookup;
        $this->fetchDefaultPrice = $fetchDefaultPrice;
        $this->priceProcessor = $priceProcessor;
        $this->logger = $logger;
    }

    public function execute(string $sku): float
    {
        $defaultPrice = $this->fetchDefaultPrice->execute($sku);
        $this->productLookup->saveAttribute($sku, Attributes::DEFAULT_DECORATION_CHARGE, round($defaultPrice, 2));

        $this->logger->info('Saving new displayed price: ', [
            'sku' => $sku,
            'price' => $defaultPrice
        ]);

        $this->priceProcessor->reindexList([$this->productLookup->getIdFromSku($sku)]);

        return $defaultPrice;
    }
}
