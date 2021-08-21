<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 8/21/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ProductDecorator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order\Item as OrderItem;
use SwiftOtter\ProductDecorator\Action\PrintSpecToArray;

class AdminProductDetails implements ArgumentInterface
{
    /** @var OrderItem */
    private $item;

    /** @var PrintSpecToArray */
    private $printSpecToArray;

    public function __construct(PrintSpecToArray $printSpecToArray)
    {
        $this->printSpecToArray = $printSpecToArray;
    }

    public function getItem(): ?OrderItem
    {
        return $this->item;
    }

    public function setItem(OrderItem $item): void
    {
        $this->item = $item;
    }

    public function getDetails(): array
    {
        return [];
    }
}
