<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/5/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Catalog\Model;

use Magento\Framework\Model\AbstractModel;
use SwiftOtter\Catalog\Model\ResourceModel\ProductMessage as ProductMessageResource;

class ProductMessage extends AbstractModel
{
    const COL_SKU = 'sku';
    const COL_COUNTRY_ID = 'country_id';
    const COL_MESSAGE = 'message';

    protected function _construct()
    {
        $this->_init(ProductMessageResource::class);
    }

    public function getSku(): string
    {
        return $this->getData(self::COL_SKU);
    }

    public function getCountryId(): string
    {
        return $this->getData(self::COL_COUNTRY_ID);
    }

    public function getMessage(): string
    {
        return $this->getData(self::COL_MESSAGE);
    }
}
