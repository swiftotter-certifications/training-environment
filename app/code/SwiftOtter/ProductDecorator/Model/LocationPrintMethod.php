<?php
declare(strict_types=1);

/**
 * @by SwiftOtter, Inc. 07/30/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use SwiftOtter\ProductDecorator\Api\Data\LocationPrintMethodInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\LocationPrintMethod as LocationPrintMethodResourceModel;

class LocationPrintMethod extends AbstractModel implements LocationPrintMethodInterface
{
    protected $_eventPrefix = 'swiftotter_productdecorator_location_printmethod';

    protected $_eventObject = 'productdecorator_location_printmethod';

    private const ID  = 'id';

    private const SIDE_LOCATION_ID = 'location_id';

    private const PRINT_METHOD_ID = 'print_method_id';

    private const SKU = 'sku';

    public function _construct()
    {
        $this->_init(LocationPrintMethodResourceModel::class);
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getLocationId(): int
    {
        return (int)$this->getData(self::SIDE_LOCATION_ID);
    }

    public function getPrintMethodId(): int
    {
        return (int)$this->getData(self::PRINT_METHOD_ID);
    }

    public function getSku(): string
    {
        return (string)$this->getData(self::SKU);
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setLocationId(int $locationId): void
    {
        $this->setData(self::SIDE_LOCATION_ID, $locationId);
    }

    public function setPrintMethodId(int $printMethodId): void
    {
        $this->setData(self::PRINT_METHOD_ID, $printMethodId);
    }

    public function setSku(string $sku): void
    {
        $this->setData(self::SKU, $sku);
    }
}
