<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Action;

use Magento\Catalog\Model\Product\Type;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use SwiftOtter\HandlingFee\Model\HandlingFee;
use SwiftOtter\HandlingFee\Model\HandlingFeeFactory;
use SwiftOtter\HandlingFee\Model\ResourceModel\HandlingFee as HandlingFeeResource;
use SwiftOtter\HandlingFee\Api\Data\OrderHandlingFeeExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartExtensionInterfaceFactory;
use Magento\Quote\Api\Data\CartItemExtensionInterfaceFactory;

class RecalculateHandlingFeesForCart
{
    /** @var AssembleFees */
    private $buildHandlingFees;

    /** @var HandlingFeeFactory */
    private $handlingFeeFactory;

    /** @var HandlingFeeResource */
    private $handlingFeeResource;

    /** @var OrderHandlingFeeExtensionInterfaceFactory */
    private $handlingFeeExtensionFactory;

    /** @var CartItemExtensionInterfaceFactory */
    private $cartItemExtensionFactory;

    /** @var CartExtensionInterfaceFactory */
    private $cartExtensionInterfaceFactory;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var StoreManagerInterface */
    private $storeManager;

    public function __construct(
        AssembleFees $buildHandlingFees,
        HandlingFeeFactory $handlingFeeFactory,
        HandlingFeeResource $handlingFeeResource,
        OrderHandlingFeeExtensionInterfaceFactory $orderHandlingFeeExtensionFactory,
        CartItemExtensionInterfaceFactory $cartItemExtensionFactory,
        CartExtensionInterfaceFactory $cartExtensionInterfaceFactory,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->buildHandlingFees = $buildHandlingFees;
        $this->handlingFeeFactory = $handlingFeeFactory;
        $this->handlingFeeResource = $handlingFeeResource;
        $this->handlingFeeExtensionFactory = $orderHandlingFeeExtensionFactory;
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->cartExtensionInterfaceFactory = $cartExtensionInterfaceFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function execute(CartModel $cart)
    {
        $this->handlingFeeResource->deleteByIds($this->handlingFeeResource->getHandlingFeesByCartId((int)$cart->getId()));

        $handlingFees = $this->buildHandlingFees->execute($this->getSimpleProducts($cart));
        $handlingFeeModels = array_map(function($details) use ($cart) {
            return $this->buildHandlingFeeModel($cart, $details);
        }, $handlingFees);

        $handlingFeeModels = array_filter($handlingFeeModels);

        foreach ($handlingFeeModels as $model) {
            $this->handlingFeeResource->save($model);
        }

        $attributes = $cart->getExtensionAttributes() ?: $this->cartExtensionFactory->create();
        $attributes->setHandlingFeeDetails($handlingFeeModels);

        $cart->setExtensionAttributes($attributes);
    }

    private function buildHandlingFeeModel(CartModel $cart, array $details): ?HandlingFee
    {
        if (!isset($details[AssembleFees::ATTR_PRODUCTS])) {
            return null;
        }

        $handlingFee = $this->handlingFeeFactory->create();

        $attributes = $this->handlingFeeExtensionFactory->create();
        $attributes->setQuoteItems($details[AssembleFees::ATTR_PRODUCTS]);
        $handlingFee->setExtensionAttributes($attributes);

        $handlingFee->setTotal(
            $this->getFeeAmount((int)$cart->getStoreId(), ScopeInterface::SCOPE_STORE)
        );

        $handlingFee->setBaseTotal(
            $this->getFeeAmount(
                (int)$this->storeManager->getStore($cart->getStoreId())->getWebsiteId(),
                ScopeInterface::SCOPE_WEBSITE
            )
        );

        /** @var CartModel\Item $cartItem */
        foreach ($details[AssembleFees::ATTR_PRODUCTS] as $cartItem) {
            $cartExtension = $cartItem->getExtensionAttributes() ?: $this->cartItemExtensionFactory->create();
            $cartExtension->setHandlingFeeDetails($handlingFee);

            $cartItem->setExtensionAttributes($cartExtension);
        }

        return $handlingFee;
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

    private function getFeeAmount(int $storeId, string $scope): float
    {
        return (float)$this->scopeConfig->getValue(
            'sales/palletizing/fee_amount',
            $scope,
            $storeId
        );
    }
}
