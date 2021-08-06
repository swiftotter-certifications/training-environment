<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\LocationInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SwiftOtter\ProductDecorator\Model\ResourceModel\Location as LocationResource;

class Location extends AbstractModel implements
    LocationInterface,
    IdentityInterface
{
    const CACHE_TAG = 'catalog_location';

    protected $_cacheTag = 'catalog_location';

    protected $_eventPrefix = 'swiftotter_catalog_location';

    protected $_eventObject = 'catalog_location';

    private const ID  = 'id';

    private const NAME = 'name';

    private const CODE = 'code';

    private const SORT_ORDER = 'sort_order';

    protected function _construct()
    {
        $this->_init(LocationResource::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    public function getCode(): string
    {
        return (string)$this->getData(self::CODE);
    }

    public function getSortOrder(): ?int
    {
        return $this->getData(self::SORT_ORDER) ? (int) $this->getData(self::SORT_ORDER) : null;
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function setCode(string $code): void
    {
        $this->setData(self::CODE, $code);
    }

    public function setSortOrder($sortOrder): void
    {
        $this->setData(self::SORT_ORDER, $sortOrder);
    }

    public function beforeSave()
    {
        if (!$this->getSortOrder()) {
            $this->setSortOrder(NULL);
        }
        parent::beforeSave();
    }
}
