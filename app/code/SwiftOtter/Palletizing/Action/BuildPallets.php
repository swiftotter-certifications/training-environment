<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Action;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Item as CartItem;
use Psr\Log\LoggerInterface;
use SwiftOtter\Palletizing\Attributes;
use SwiftOtter\Palletizing\Model\ProductResolver;

class BuildPallets
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
        $pallets = [];

        $currentPallet = 0;
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
                ? ceil($cartItem->getQtyOrdered() / $product->getData(Attributes::CASE_PACK_QTY))
                : 1;

            $productSize = $cases * $product->getData(Attributes::CASE_CUBIC_SIZE);

            if ($cubicSize + $productSize > $this->getCubicSizePerPallet()) {
                $pallets[$currentPallet][self::ATTR_SIZE] = $cubicSize;

                $cubicSize = 0;
                $currentPallet += 1;
            }

            $cubicSize += $productSize;

            if (!isset($pallets[$currentPallet])) {
                $pallets[$currentPallet] = [
                    self::ATTR_PRODUCTS => []
                ];
            }

            $pallets[$currentPallet][self::ATTR_PRODUCTS][] = $cartItem;
        }

        return $pallets;
    }

    private function getCubicSizePerPallet(): float
    {
        $size = $this->scopeConfig->getValue('sales/palletizing/max_size_per_pallet');

        if ($padding = $this->scopeConfig->getValue('sales/palletizing/pallet_padding')) {
            $size = $size - ($size * ($padding / 100));
        }

        return $size;
    }
}