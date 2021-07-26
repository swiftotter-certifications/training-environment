<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/22/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Palletizing\Plugin;

use Magento\Catalog\Model\Product\Type;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;
use Magento\Quote\Model\Quote as CartModel;
use SwiftOtter\Palletizing\Action\BuildPallets;
use SwiftOtter\Palletizing\Api\Data\OrderPalletExtensionInterfaceFactory;
use SwiftOtter\Palletizing\Model\Pallet;
use SwiftOtter\Palletizing\Model\PalletFactory;
use SwiftOtter\Palletizing\Model\ProductResolver;
use SwiftOtter\Palletizing\Model\ResourceModel\Pallet as PalletResource;

class BuildPalletsForQuote
{
    /** @var BuildPallets */
    private $buildPallets;

    /** @var PalletFactory */
    private $palletFactory;

    /** @var PalletResource */
    private $palletResource;

    /** @var OrderPalletExtensionInterfaceFactory */
    private $orderPalletExtensionFactory;

    /** @var CartItemExtensionInterfaceFactory */
    private $cartItemExtensionFactory;

    /** @var CartExtensionInterfaceFactory */
    private $cartExtensionFactory;

    public function __construct(
        BuildPallets $buildPallets,
        PalletFactory $palletFactory,
        PalletResource $palletResource,
        OrderPalletExtensionInterfaceFactory $orderPalletExtensionFactory,
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory,
        CartExtensionInterfaceFactory $cartExtensionInterfaceFactory
    ) {
        $this->buildPallets = $buildPallets;
        $this->palletFactory = $palletFactory;
        $this->palletResource = $palletResource;
        $this->orderPalletExtensionFactory = $orderPalletExtensionFactory;
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->cartExtensionFactory = $cartExtensionInterfaceFactory;
    }

    public function beforeCollectTotals(CartModel $cart)
    {
        if (!$cart->getId()) {
            return null;
        }

        $this->palletResource->deleteByIds($this->palletResource->getPalletsByCartId((int)$cart->getId()));

        $pallets = $this->buildPallets->execute($this->getSimpleProducts($cart));
        $palletModels = array_map(function($details) {
            return $this->buildPalletModel($details);
        }, $pallets);

        $palletModels = array_filter($palletModels);

        foreach ($palletModels as $palletModel) {
            $this->palletResource->save($palletModel);
        }

        $attributes = $cart->getExtensionAttributes() ?: $this->cartExtensionFactory->create();
        $attributes->setPalletDetails($palletModels);

        $cart->setExtensionAttributes($attributes);

        return null;
    }

    private function buildPalletModel(array $details): ?Pallet
    {
        if (!isset($details[BuildPallets::ATTR_PRODUCTS])) {
            return null;
        }

        $pallet = $this->palletFactory->create();

        $attributes = $this->orderPalletExtensionFactory->create();
        $attributes->setQuoteItems($details[BuildPallets::ATTR_PRODUCTS]);

        /** @var CartModel\Item $cartItem */
        foreach ($details[BuildPallets::ATTR_PRODUCTS] as $cartItem) {
            $cartExtension = $cartItem->getExtensionAttributes() ?: $this->cartItemExtensionFactory->create();
            $cartExtension->setPalletDetails($pallet);
        }

        return $pallet;
    }

    private function getSimpleProducts(CartModel $cart): array
    {
        $output = [];

        /** @var CartModel\Item $item */
        foreach ($cart->getAllItems() as $item) {
            if ($item->getProductType() !== Type::TYPE_SIMPLE) {
                continue;
            }

            $output[] = $item;
        }

        return $output;
    }
}