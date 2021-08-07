<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 07/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Model\PrintSpec;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use SwiftOtter\ProductDecorator\Api\Data\PrintSpec\QuoteItemInterface;
use SwiftOtter\ProductDecorator\Model\ResourceModel\PrintSpec\QuoteItem as PrintSpecQuoteItemResourceModel;
use SwiftOtter\Utils\Model\StrictTypeTrait;

class QuoteItem extends AbstractModel implements IdentityInterface, QuoteItemInterface
{
    use StrictTypeTrait;

    const CACHE_TAG = 'print_spec_quote_item';

    protected $_cacheTag = 'prints_spec_quote_item';

    protected $_eventPrefix = 'swiftotter_print_spec_quote_item';

    protected $_eventObject = 'print_spec_quote_item';

    private const ID  = 'id';

    private const QUOTE_ITEM_ID = 'quote_item_id';

    private const PRINT_SPEC_ID = 'print_spec_id';

    protected function _construct()
    {
        $this->_init(PrintSpecQuoteItemResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId(): ?int
    {
        return $this->getData(self::ID) ? (int) $this->getData(self::ID) : null;
    }

    public function getQuoteItemId(): ?int
    {
        return $this->nullableInt($this->getData(self::QUOTE_ITEM_ID));
    }

    public function getPrintSpecId(): ?int
    {
        return $this->nullableInt($this->getData(self::PRINT_SPEC_ID));
    }

    public function setId($id): void
    {
        $this->setData(self::ID, $id);
    }

    public function setQuoteItemId(?int $value): void
    {
        $this->setData(self::QUOTE_ITEM_ID, $value);
    }

    public function setPrintSpecId(?int $value): void
    {
        $this->setData(self::PRINT_SPEC_ID, $value);
    }
}
