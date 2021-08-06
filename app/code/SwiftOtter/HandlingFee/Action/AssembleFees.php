<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Action;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Item as CartItem;
use Psr\Log\LoggerInterface;
use SwiftOtter\HandlingFee\Attributes;
use SwiftOtter\HandlingFee\Model\ProductResolver;

class AssembleFees
{
    const ATTR_SIZE = 'size';
    const ATTR_PRODUCTS = 'products';

    /** @var ProductResolver */
    private $productResolver;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        ProductResolver $productResolver,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->productResolver = $productResolver;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function execute(array $cartItems): array
    {
        $fees = [];

        $currentFee = 0;
        $cubicSize = 0;

        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $product = $this->productResolver->get($cartItem->getSku());

            if (!$product->getSku()
                || !$product->getData(Attributes::CASE_PACK_QTY)
                || !$product->getData(Attributes::CASE_CUBIC_SIZE)) {
                $this->logger->error('Product SKU or attributes were missing for: ' . $cartItem->getId() . '.');

                continue;
            }

            $cases = $product->getData(Attributes::CASE_PACK_QTY)
                ? ceil($cartItem->getQty() / $product->getData(Attributes::CASE_PACK_QTY))
                : 1;

            $productSize = $cases * $product->getData(Attributes::CASE_CUBIC_SIZE);

            if ($cubicSize + $productSize > $this->getCubicSizePerHandlingFee()) {
                $fees[$currentFee][self::ATTR_SIZE] = $cubicSize;

                $cubicSize = 0;
                $currentFee += 1;
            }

            $cubicSize += $productSize;

            if (!isset($fees[$currentFee])) {
                $fees[$currentFee] = [
                    self::ATTR_PRODUCTS => []
                ];
            }

            $fees[$currentFee][self::ATTR_PRODUCTS][] = $cartItem;
        }

        return $fees;
    }

    private function getCubicSizePerHandlingFee(): float
    {
        $size = $this->scopeConfig->getValue('sales/handling_fee/max_size_per_fee');

        if ($padding = $this->scopeConfig->getValue('sales/handling_fee/fee_padding')) {
            $size = $size - ($size * ($padding / 100));
        }

        return $size;
    }
}
