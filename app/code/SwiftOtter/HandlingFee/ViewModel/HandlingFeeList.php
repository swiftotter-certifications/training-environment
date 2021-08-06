<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFeeOrderItem;
use SwiftOtter\HandlingFee\Service\Order as OrderService;

class HandlingFeeList implements ArgumentInterface
{
    /** @var OrderService */
    private $orderService;

    /** @var HandlingFeeOrderItem */
    private $handlingFeeOrderItem;

    public function __construct(OrderService $orderService, HandlingFeeOrderItem $handlingFeeOrderItem)
    {
        $this->orderService = $orderService;
        $this->handlingFeeOrderItem = $handlingFeeOrderItem;
    }

    public function getPallets()
    {

    }
}
