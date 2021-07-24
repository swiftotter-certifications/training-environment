<?php
/**
 * Copyright: 2017 (c) SwiftOtter Studios
 *
 * @author    Joseph Maxwell
 * @copyright Swift Otter Studios, 12/7/17
 * @package   default
 **/

namespace SwiftOtter\CategoryAsProduct\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use SwiftOtter\CategoryAsProduct\Attributes;

class Type extends \Magento\Catalog\Model\Product\Type\AbstractType
{
    const TYPE_ID = 'category_as_product';

    private $collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($catalogProductOption, $eavConfig, $catalogProductType, $eventManager, $fileStorageDb, $filesystem, $coreRegistry, $logger, $productRepository, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intentionally empty
    }

    public function getUrl(\Magento\Catalog\Model\Product $product)
    {
        $attribute = $product->getCustomAttribute(Attributes::REDIRECT_TO_CATEGORY);
        if (!$attribute || !$attribute->getValue()) {
            return $product->getProductUrl();
        }

        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $this->collectionFactory->create();
        $categoryCollection->addFieldToFilter('entity_id', ['eq' => $attribute->getValue()]);
        $categoryCollection->addAttributeToSelect(['name', 'url_key']);
        /** @var Category $category */
        $category = $categoryCollection->getFirstItem();

        return $category->getUrl();
    }
}