<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/6/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\CategoryAsProduct\Plugin\Catalog\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use SwiftOtter\CategoryAsProduct\Model\Product\Type;

class Product
{
    const KEY_SORT = 'news_from_date';
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    public function __construct(\Magento\Framework\Stdlib\DateTime\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function afterBeforeSave(\Magento\Catalog\Model\Product $context)
    {
        if ($context->getTypeId() === Type::TYPE_ID && !$context->getData(self::KEY_SORT)) {
            $context->setData(self::KEY_SORT, $context->getData(ProductInterface::CREATED_AT) ?? $this->dateTime->gmtDate());
        }

        return $context;
    }
}
