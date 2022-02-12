<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Plugin\Catalog\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\Stdlib\DateTime\DateTime;
use SwiftOtter\CategoryAsProduct\Model\Product\Type;

class Product
{
    const KEY_SORT = 'news_from_date';

    private DateTime $dateTime;

    public function __construct(DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function afterBeforeSave(ProductModel $context)
    {
        if ($context->getTypeId() === Type::TYPE_ID && !$context->getData(self::KEY_SORT)) {
            $context->setData(self::KEY_SORT, $context->getData(ProductInterface::CREATED_AT) ?? $this->dateTime->gmtDate());
        }

        return $context;
    }
}
