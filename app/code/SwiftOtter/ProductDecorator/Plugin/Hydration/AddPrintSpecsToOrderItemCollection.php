<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 01/09/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\Plugin\Hydration;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as OrderItemCollection;
use SwiftOtter\ProductDecorator\Action\Hydration\AddPrintSpecsToOrderItems as Action;

class AddPrintSpecsToOrderItemCollection
{
    /** @var Action */
    private $addPrintSpecsToOrderItems;

    public function __construct(
        Action $addPrintSpecsToOrderItems
    ) {
        $this->addPrintSpecsToOrderItems = $addPrintSpecsToOrderItems;
    }

    public function afterLoadWithFilter(OrderItemCollection $subject, OrderItemCollection $result): OrderItemCollection
    {
        $this->addPrintSpecsToOrderItems->execute($result->getItems());

        return $result;
    }
}
