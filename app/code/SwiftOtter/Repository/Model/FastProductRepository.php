<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/6/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Repository\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Catalog\Model\ProductRepository as OriginalProductRepository;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use Magento\Framework\Exception\NoSuchEntityException;
use SwiftOtter\Repository\Api\FastProductRepositoryInterface;
use SwiftOtter\Utils\Model\ResourceModel\ProductLookup;

class FastProductRepository implements FastProductRepositoryInterface
{
    const CACHE_PRODUCT_KEY = 'product';
    const CACHE_ATTRIBUTES_KEY = 'attributes';
    const CACHE_KEY_READS = 'reads';

    /** @var OriginalProductRepository */
    private $originalProductRepository;

    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    private $cache = [];

    /** @var ProductLookup */
    private $productLookup;

    /** @var JoinProcessorInterface */
    private $extensionAttributesJoinProcessor;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var ReadExtensions */
    private $readExtensions;

    /** @var SearchResultsFactory */
    private $searchResultsFactory;

    /** @var int */
    private $maximumCache;

    /** @var ProductResource */
    private $productResource;

    public function __construct(
        OriginalProductRepository $originalProductRepository,
        ProductCollectionFactory $productCollectionFactory,
        ProductLookup $productLookup,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        ReadExtensions $readExtensions,
        SearchResultsFactory $searchResultsFactory,
        ProductResource $productResource,
        int $maximumCache = 100
    ) {
        $this->originalProductRepository = $originalProductRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productLookup = $productLookup;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->readExtensions = $readExtensions;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->maximumCache = $maximumCache;
        $this->productResource = $productResource;
    }

    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false, ?array $attributes = null): ProductInterface
    {
        if ($attributes === null || $forceReload) {
            return $this->callWithDisabledPlugins(__FUNCTION__, func_get_args());
        }

        $sku = $this->productLookup->getSkuFromEntityId($productId);
        if (!$sku) {
            throw new NoSuchEntityException(__('%1 is not a valid product.', $productId));
        }

        return $this->buildFromCachedProduct($sku, $storeId, $attributes);
    }

    public function get($sku, $editMode = false, $storeId = null, $forceReload = false, ?array $attributes = null)
    {
        if ($attributes === null || $forceReload) {
            return $this->callWithDisabledPlugins(__FUNCTION__, func_get_args());
        }

        return $this->buildFromCachedProduct($sku, $storeId, $attributes);
    }

    public function getList(SearchCriteriaInterface $searchCriteria, ?array $attributes = null)
    {
        if ($attributes === null) {
            return $this->callWithDisabledPlugins(__FUNCTION__, func_get_args());
        }

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);

        $collection->addAttributeToSelect($attributes); // << SwiftOtter: biggest change occurs here
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
        $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();

        $collection->addCategoryIds();
        $this->addExtensionAttributes($collection);
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        foreach ($collection->getItems() as $product) {
            $this->addRead($product->getSku(), $product->getStoreId());
            $this->saveCacheAndMergeExistingValue($product, $attributes, $product->getStoreId());
        }

        return $searchResult;
    }

    private function addExtensionAttributes(Collection $collection) : Collection
    {
        foreach ($collection->getItems() as $item) {
            $this->readExtensions->execute($item);
        }
        return $collection;
    }

    public function save(ProductInterface $product, $saveOptions = false)
    {
        $this->cleanCache($product->getSku());
        return $this->originalProductRepository->{__FUNCTION__}(...func_get_args());
    }

    public function delete(ProductInterface $product)
    {
        $this->cleanCache($product->getSku());
        return $this->originalProductRepository->{__FUNCTION__}(...func_get_args());
    }

    public function deleteById($sku)
    {
        $this->cleanCache($sku);
        return $this->originalProductRepository->{__FUNCTION__}(...func_get_args());
    }

    private function getCacheKey($sku, $storeId = null)
    {
        return mb_strtolower(trim($sku)) . '-' . ((int)$storeId);
    }

    private function getCached($sku, $storeId = null): array
    {
        return $this->cache[$this->getCacheKey($sku, $storeId)] ??
            [
                self::CACHE_PRODUCT_KEY => null,
                self::CACHE_ATTRIBUTES_KEY => [],
                self::CACHE_KEY_READS => 0
            ];
    }

    private function isFullyCached($sku, $storeId = null, $attributes = []): bool
    {
        $cached = $this->getCached($sku, $storeId);

        return !empty($cached[self::CACHE_PRODUCT_KEY])
            && !count(array_diff($attributes, $cached[self::CACHE_ATTRIBUTES_KEY]));
    }

    private function saveCacheAndMergeExistingValue(ProductInterface $product, array $attributesToSelect, $storeId = null): void
    {
        $key = $this->getCacheKey($product->getSku(), $storeId);
        $existing = $this->getCached($product->getSku(), $storeId);

        if (!empty($existing[self::CACHE_PRODUCT_KEY])) {
            $product->addData($existing[self::CACHE_PRODUCT_KEY]->getData());
        }

        $this->cache[$key] = [
            self::CACHE_PRODUCT_KEY => $product,
            self::CACHE_ATTRIBUTES_KEY => array_merge($existing[self::CACHE_ATTRIBUTES_KEY] ?? [], $attributesToSelect),
            self::CACHE_KEY_READS => $existing[self::CACHE_KEY_READS] ?? 1
        ];
    }

    private function cleanCache(string $sku)
    {
        foreach ($this->cache as $key) {
            if (strpos($key, mb_strtolower(trim($sku)) . '-') !== false) {
                unset($this->cache[$key]);
            }
        }
    }

    private function buildFromCachedProduct(string $sku, $storeId, ?array $attributes): ProductInterface
    {
        $this->addRead($sku, $storeId);

        $cached = $this->getCached($sku, $storeId);
        $attributesToSelect = array_diff($attributes, $cached[self::CACHE_ATTRIBUTES_KEY] ?? []);

        if (!empty($cached[self::CACHE_PRODUCT_KEY]) && !count($attributes)) {
            return $cached[self::CACHE_PRODUCT_KEY];
        }

        // Escape hatch if we only need one attribute. Faster is better :).
        if (count($attributesToSelect) === 1 && !empty($cached[self::CACHE_PRODUCT_KEY])) {
            return $this->loadSingleAttributeForProduct($cached[self::CACHE_PRODUCT_KEY], $storeId, $attributesToSelect);
        }

        $product = $this->productCollectionFactory->create()
            ->setStoreId($storeId)
            ->addFieldToFilter('sku', $sku)
            ->addAttributeToSelect($attributesToSelect)
            ->getFirstItem();

        if (!$product->getId()) {
            throw new NoSuchEntityException(__('%1 is not a valid product to load.', $sku));
        }

        if (!empty($cached[self::CACHE_PRODUCT_KEY])) {
            $cachedProduct = $cached[self::CACHE_PRODUCT_KEY];
            $cachedProduct->addData($product->getData());
        } else {
            $cachedProduct = $product;
        }

        $this->saveCacheAndMergeExistingValue($cachedProduct, $attributesToSelect, $storeId);

        return $cachedProduct;
    }

    private function addRead(string $sku, $storeId = null): void
    {
        $key = $this->getCacheKey($sku, $storeId);
        $this->cache[$key][self::CACHE_KEY_READS] = ($this->cache[$key][self::CACHE_KEY_READS] ?? 0) + 1;
    }

    private function callWithDisabledPlugins(string $method, array $args)
    {
        if (strpos(get_class($this->originalProductRepository), 'Interceptor') === false) {
            return $this->originalProductRepository->$method(...$args);
        }

        $property = new \ReflectionProperty($this->originalProductRepository, 'subjectType');
        $property->setAccessible(true);
        $originalType = $property->getValue($this->originalProductRepository);
        $property->setValue($this->originalProductRepository, 'invalidType');

        $output = $this->originalProductRepository->$method(...$args);

        $property->setValue($this->originalProductRepository, $originalType);
        $property->setAccessible(false);
        return $output;
    }

    private function loadSingleAttributeForProduct($cached, $storeId, ?array $attributesToSelect)
    {
        $cachedProduct = $cached;
        $attribute = reset($attributesToSelect);

        $value = $this->productResource->getAttributeRawValue(
            $cachedProduct->getId(),
            $attribute,
            $storeId
        );

        $cachedProduct->setData($attribute, $value);
        $this->saveCacheAndMergeExistingValue($cachedProduct, $attributesToSelect, $storeId);

        return $cachedProduct;
    }
}
