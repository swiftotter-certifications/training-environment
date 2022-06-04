<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Model;

use SwiftOtter\Teaching\Api\InvoiceStageInterface;
use SwiftOtter\Teaching\Api\InvoiceStageInterfaceFactory;
use SwiftOtter\Teaching\Api\OrderStageInterface;
use SwiftOtter\Teaching\Api\OrderStageInterfaceFactory;

class SingleClassDemo
{

    private InvoiceStageInterfaceFactory $invoiceStageFactory;
    private OrderStageInterfaceFactory $orderStageFactory;

    public function __construct(InvoiceStageInterfaceFactory $invoiceStageFactory, OrderStageInterfaceFactory $orderStageFactory)
    {
        $this->invoiceStageFactory = $invoiceStageFactory;
        $this->orderStageFactory = $orderStageFactory;
    }

    public function get($order)
    {
        if ($order instanceof InvoiceStageInterface) {
            return $this->invoiceStageFactory->create();
        }
    }
}
