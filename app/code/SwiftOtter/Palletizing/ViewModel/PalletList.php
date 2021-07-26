<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use SwiftOtter\Palletizing\Model\ResourceModel\PalletOrderItem;
use SwiftOtter\Palletizing\Service\Order as OrderService;

class PalletList implements ArgumentInterface
{
    /** @var OrderService */
    private $orderService;

    /** @var PalletOrderItem */
    private $palletOrderItem;

    public function __construct(OrderService $orderService, PalletOrderItem $palletOrderItem)
    {
        $this->orderService = $orderService;
        $this->palletOrderItem = $palletOrderItem;
    }

    public function getPallets()
    {

    }
}