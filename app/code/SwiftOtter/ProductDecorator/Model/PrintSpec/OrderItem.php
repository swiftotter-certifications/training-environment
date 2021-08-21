<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\PrintSpec;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpec\ItemInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\OrderItem as PrintSpecOrderItemResourceModel;
use SwiftOtter\Utils\Model\StrictTypeTrait;

class OrderItem extends AbstractModel implements IdentityInterface, ItemInterface
{
    use StrictTypeTrait;

    const CACHE_TAG = 'print_spec_order_item';

    protected $_cacheTag = 'prints_spec_order_item';

    protected $_eventPrefix = 'swiftotter_print_spec_order_item';

    protected $_eventObject = 'print_spec_order_item';

    private const ID  = 'id';

    private const ORDER_ITEM_ID = 'order_item_id';

    private const PRINT_SPEC_ID = 'print_spec_id';

    protected function _construct()
    {
        $this->_init(PrintSpecOrderItemResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getItemId(): ?int
    {
        return $this->nullableInt($this->getData(self::ORDER_ITEM_ID));
    }

    public function getPrintSpecId(): ?int
    {
        return $this->nullableInt($this->getData(self::PRINT_SPEC_ID));
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setItemId(?int $value): void
    {
        $this->setData(self::ORDER_ITEM_ID, $value);
    }

    public function setPrintSpecId(?int $value): void
    {
        $this->setData(self::PRINT_SPEC_ID, $value);
    }
}
