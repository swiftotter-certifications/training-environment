<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use SwiftOtter\ProductDecorator\Api\Data\PrintMethodInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod as PrintMethodResource;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintMethod\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class PrintMethod extends AbstractModel implements
    PrintMethodInterface,
    IdentityInterface
{
    const CACHE_TAG = 'catalog_print_method';

    protected $_cacheTag = 'swiftotter_print_method';

    protected $_eventPrefix = 'swiftotter_catalog_print_method';

    protected $_eventObject = 'print_method';

    private const ID  = 'id';

    private const NAME = 'name';

    private const PRICE_TYPE = 'price_type';

    protected function _construct()
    {
        $this->_init(PrintMethodResource::class);
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

    public function getPriceType(): string
    {
        return (string)$this->getData(self::PRICE_TYPE);
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function setPriceType(string $value): void
    {
        $this->setData(self::PRICE_TYPE, $value);
    }
}
