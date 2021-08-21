<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/20/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\DownloadProduct\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProductType;
use Magento\Framework\App\Area;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use SwiftOtter\Catalog\Model\PriceCalculatorFactory;
use SwiftOtter\Catalog\Model\ResourceModel\ProductMessage as ProductMessageResource;
use SwiftOtter\DownloadProduct\Api\Data\ProductDetailInterface;
use SwiftOtter\DownloadProduct\Api\PriceResponseInterface;
use SwiftOtter\DownloadProduct\Attributes;
use SwiftOtter\DownloadProduct\Service\GetLastPurchase;
use SwiftOtter\DownloadProduct\Service\TestLookup;

class ProductDetail implements ProductDetailInterface
{
    const REQUIRED_ATTRIBUTES = [
        \SwiftOtter\DownloadProduct\Attributes::HELP_TEXT,
        \SwiftOtter\Catalog\Attributes::ATTRIBUTE_ADDON_HELPTEXT,
        \SwiftOtter\DownloadProduct\Attributes::ALTERNATIVE_NAME,
        \SwiftOtter\DownloadProduct\Attributes::IS_PREFERRED,
        \SwiftOtter\DownloadProduct\Attributes::SORT_ORDER,
        'name',
        'price'
    ];

    /** @var ProductInterface */
    private $product;

    /** @var PriceResponseFactory */
    private $priceResponseFactory;

    /** @var PriceCalculatorFactory */
    private $priceCalculatorFactory;

    /** @var Image */
    private $imageHelper;

    /** @var ProductFactory */
    private $productFactory;

    /** @var Emulation */
    private $appEmulation;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var ProductCollectionFactory */
    private $productCollectionFactory;

    /** @var GetLastPurchase */
    private $getLastPurchase;

    /** @var TestLookup */
    private $testLookup;

    /** @var int|null */
    private $qty;

    /** @var string */
    private $type;

    /** @var Product\Type */
    private $typeFactory;

    /** @var array|null */
    private $children;

    /** @var string|null */
    private $country;

    /** @var ProductMessageResource */
    private $productMessageResource;

    public function __construct(
        Emulation $appEmulation,
        StoreManagerInterface $storeManager,
        ProductFactory $productFactory,
        Image $imageHelper,
        ProductInterface $product,
        ProductCollectionFactory $productCollectionFactory,
        ProductMessageResource $productMessageResource,
        string $type,
        ?string $country = null,
        ?int $qty = null,
        ?array $children = [],
        ?array $addons = []
    ) {
        $this->product = $product;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->appEmulation = $appEmulation;
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->qty = $qty;
        $this->type = $type;
        $this->children = $children;
        $this->country = $country;
        $this->productMessageResource = $productMessageResource;
        $this->addons = $addons;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function getSku(): string
    {
        return $this->product->getSku();
    }

    public function getPrice(): PriceResponseInterface
    {
        $price = $this->priceCalculatorFactory->create([
            'discount' => '',
            'productId' => (int)$this->product->getId()
        ]);

        return $this->priceResponseFactory->create([
            'priceCalculator' => $price,
            'name' => $this->product->getName() ?? '',
            'sku' => $this->product->getSku() ?? '',
        ]);
    }

    public function getName(): string
    {
        return $this->product->getName();
    }

    public function getShortName(): string
    {
        $attributeList = [Attributes::ALTERNATIVE_NAME];

        if ($this->getDisplayType() === self::DISPLAY_TYPE_ADDON) {
            array_unshift($attributeList, \SwiftOtter\Catalog\Attributes::ATTRIBUTE_ADDON_NAME);
        }

        foreach ($attributeList as $attribute) {
            if ($text = $this->product->getData($attribute)) {
                return $text;
            } elseif ($this->product->getCustomAttribute($attribute)
                && $this->product->getCustomAttribute($attribute)->getValue()) {
                return $this->product->getCustomAttribute($attribute)->getValue();
            }
        }

        return $this->product->getName();
    }

    public function getHelpText(): string
    {
        $attributeList = [Attributes::HELP_TEXT];

        if ($this->getDisplayType() === self::DISPLAY_TYPE_ADDON) {
            array_unshift($attributeList, \SwiftOtter\Catalog\Attributes::ATTRIBUTE_ADDON_HELPTEXT);
        }

        foreach ($attributeList as $attribute) {
            if ($text = $this->product->getData($attribute)) {
                return $text;
            } elseif ($this->product->getCustomAttribute($attribute)
                && $this->product->getCustomAttribute($attribute)->getValue()) {
                return $this->product->getCustomAttribute($attribute)->getValue();
            }
        }

        return '';
    }

    public function getType(): string
    {
        if (stripos($this->product->getName(), 'study guide') !== false) {
            return self::TYPE_STUDY_GUIDE;
        } elseif (stripos($this->product->getName(), 'practice') !== false) {
            return self::TYPE_PRACTICE_TEST;
        } elseif (stripos($this->product->getName(), 'course') !== false) {
            return self::TYPE_PREP_COURSE;
        }

        return $this->product->getTypeId();
    }

    public function getImage(): string
    {
        $storeId = $this->storeManager->getStore()->getId();

        $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);

        $image = $this->imageHelper->init($this->product, 'product_base_image')
            ->constrainOnly(true)
            ->keepFrame(false)
            ->keepAspectRatio(true)
            ->resize(1200, 1200)
            ->getUrl();

        $this->appEmulation->stopEnvironmentEmulation();

        return $image;
    }

    public function getIsPreferred(): bool
    {
        if ($text = $this->product->getData(Attributes::IS_PREFERRED)) {
            return $text;
        } elseif ($this->product->getCustomAttribute(Attributes::IS_PREFERRED)
            && $this->product->getCustomAttribute(Attributes::IS_PREFERRED)->getValue()) {
            return $this->product->getCustomAttribute(Attributes::IS_PREFERRED)->getValue();
        }

        return false;
    }

    public function getSortOrder(): int
    {
        if ($text = $this->product->getData(Attributes::SORT_ORDER)) {
            return (int)$text;
        } elseif ($this->product->getCustomAttribute(Attributes::SORT_ORDER)
            && $this->product->getCustomAttribute(Attributes::SORT_ORDER)->getValue()) {
            return (int)$this->product->getCustomAttribute(Attributes::SORT_ORDER)->getValue();
        }

        return 20;
    }

    public function getIsPurchased(): bool
    {
        return $this->getLastPurchase->getIsPurchased((int)$this->product->getId());
    }

    public function getIsActive(): bool
    {
        return $this->getLastPurchase->getIsActive((int)$this->product->getId());
    }

    public function getTestId(): int
    {
        if ($this->testLookup->isTest((int)$this->product->getId())) {
            return $this->getLastPurchase->getTestId((int)$this->product->getId());
        }

        return 0;
    }

    public function getDisplayType(): string
    {
        return $this->type;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getMessage(): string
    {
        if (!$this->country) {
            return '';
        }

        return $this->productMessageResource->getMessageFor($this->getSku(), $this->country) ?: '';
    }
}
