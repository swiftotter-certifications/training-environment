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
use SwiftOtter\ProductDecorator\Api\Data\TierInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Tier as TierResourceModel;

class Tier extends AbstractModel implements TierInterface
{
    protected $_eventPrefix = 'swiftotter_productdecorator_tier';

    protected $_eventObject = 'productdecorator_tier';

    private const ID  = 'id';

    private const MIN_TIER = 'min_tier';

    private const MAX_TIER = 'max_tier';

    public function _construct()
    {
        $this->_init(TierResourceModel::class);
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getMinTier(): int
    {
        return (int)$this->getData(self::MIN_TIER);
    }

    public function getMaxTier(): int
    {
        return (int)$this->getData(self::MAX_TIER);
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setMinTier(int $minTier): void
    {
        $this->setData(self::MIN_TIER, $minTier);
    }

    public function setMaxTier(int $maxTier): void
    {
        $this->setData(self::MAX_TIER, $maxTier);
    }
}
