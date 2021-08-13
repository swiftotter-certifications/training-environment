<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\PrintSpec;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpec\LocationInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\Location as PrintSpecLocationResourceModel;
use SwiftOtter\Utils\Model\StrictTypeTrait;

class Location extends AbstractModel implements IdentityInterface, LocationInterface
{
    use StrictTypeTrait;

    const CACHE_TAG = 'print_spec_location';

    protected $_cacheTag = 'prints_spec_location';

    protected $_eventPrefix = 'swiftotter_print_spec_location';

    protected $_eventObject = 'print_spec_location';

    private const ID  = 'id';

    private const PRINT_SPEC_ID = 'print_spec_id';

    private const LOCATION_ID = 'location_id';

    private const PRINT_METHOD_ID = 'print_method_id';

    private const COLORS = 'colors';

    private const DISPLAY_TEXT = 'display_text';

    protected function _construct()
    {
        $this->_init(PrintSpecLocationResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getPrintSpecId(): ?int
    {
        return $this->nullableInt($this->getData(self::LOCATION_ID));
    }

    public function getLocationId(): ?int
    {
        return $this->nullableInt($this->getData(self::LOCATION_ID));
    }

    public function getPrintMethodId(): ?int
    {
        return $this->nullableInt($this->getData(self::PRINT_METHOD_ID));
    }

    public function getColors(): array
    {
        try {
            return \Safe\json_decode($this->getData(self::COLORS), true);
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function getDisplayText(): ?string
    {
        return $this->getData(self::DISPLAY_TEXT) ?: null;
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setPrintSpecId($value): void
    {
        $this->setData(self::PRINT_SPEC_ID, $value);
    }

    public function setLocationId(?int $value): void
    {
        $this->setData(self::LOCATION_ID, $value);
    }

    public function setPrintMethodId(?int $value): void
    {
        $this->setData(self::PRINT_METHOD_ID, $value);
    }

    public function setColors(?array $value): void
    {
        $this->setData(self::COLORS, \Safe\json_encode($value));
    }

    public function setDisplayText(?string $value): void
    {
        $this->setData(self::DISPLAY_TEXT, $value);
    }
}
