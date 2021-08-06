<?php
declare(strict_types=1);

/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SwiftOtter\ProductDecorator\Api\Data\PrintChargeInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintCharge as PrintChargeResourceModel;

class PrintCharge extends AbstractModel implements PrintChargeInterface
{
    protected $_eventPrefix = 'swiftotter_productdecorator_printcharge';

    protected $_eventObject = 'productdecorator_printcharge';

    private const ID  = 'id';

    private const TIER_ID = 'tier_id';

    private const COLORS = 'colors';

    private const PRICE = 'price';

    private const META = 'meta';

    private const PRICE_TYPE = 'price_type';

    private const QUALIFIER = 'qualifier';

    private const MIN_LOOKUP = 'min_lookup';

    private const MAX_LOOKUP = 'max_lookup';

    public function _construct()
    {
        $this->_init(PrintChargeResourceModel::class);
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getTierId(): int
    {
        return (int)$this->getData(self::TIER_ID);
    }

    public function getPrice(): float
    {
        return (float)$this->getData(self::PRICE);
    }

    public function getColors(): int
    {
        return (int)$this->getData(self::COLORS);
    }

    public function getPriceType(): string
    {
        return (string)$this->getData(self::PRICE_TYPE);
    }

    public function getMinLookup(): ?int
    {
        return is_null($this->getData(self::MIN_LOOKUP)) ? null
            : (int)$this->getData(self::MIN_LOOKUP);
    }

    public function getMaxLookup(): ?int
    {
        return is_null($this->getData(self::MAX_LOOKUP)) ? null
            : (int)$this->getData(self::MAX_LOOKUP);
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setTierId(int $id): void
    {
        $this->setData(self::TIER_ID, $id);
    }

    public function setPrice(float $price): void
    {
        $this->setData(self::PRICE, $price);
    }

    public function setColors(int $colors): void
    {
        $this->setData(self::COLORS, $colors);
    }

    public function setPriceType(string $priceType): void
    {
        $this->setData(self::PRICE_TYPE, $priceType);
    }

    public function setMinLookup(?int $value): void
    {
        $this->setData(self::MIN_LOOKUP, $value);
    }

    public function setMaxLookup(?int $value): void
    {
        $this->setData(self::MAX_LOOKUP, $value);
    }
}
