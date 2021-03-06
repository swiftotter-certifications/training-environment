<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpecInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec as PrintSpecResource;
use SwiftOtter\Utils\Model\StrictTypeTrait;

class PrintSpec extends AbstractExtensibleModel implements IdentityInterface, PrintSpecInterface
{
    use StrictTypeTrait;

    const CACHE_TAG = 'print_spec';

    protected $_cacheTag = 'prints_spec';

    protected $_eventPrefix = 'swiftotter_print_spec';

    protected $_eventObject = 'print_spec';

    private const ID  = 'id';

    private const NAME = 'name';

    private const IS_DELETED = 'is_deleted';

    private const CLIENT_ID = 'client_id';

    private const CART_ID = 'cart_id';

    protected function _construct()
    {
        $this->_init(PrintSpecResource::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getName(): ?string
    {
        return (string)$this->getData(self::NAME);
    }

    public function getClientId(): ?string
    {
        return (string)$this->getData(self::CLIENT_ID);
    }

    public function getCartId(): ?int
    {
        return $this->nullableInt($this->getData(self::CART_ID));
    }

    public function getIsDeleted(): bool
    {
        return (bool)$this->getData(self::IS_DELETED);
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function setClientId(?string $value): void
    {
        $this->setData(self::CLIENT_ID, $value);
    }

    public function setCartId(?int $value): void
    {
        $this->setData(self::CART_ID, $value);
    }

    public function setIsDeleted(bool $value): void
    {
        $this->setData(self::IS_DELETED, $value);
    }

    public function getExtensionAttributes(): \SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(\SwiftOtter\ProductDecorator\Api\Data\PrintSpecExtensionInterface $attributes): void
    {
        $this->setExtensionAttributes($attributes);
    }
}
