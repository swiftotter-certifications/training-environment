<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/29/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\HandlingFee\Model\QuoteTotal;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface as ShippingAssignment;
use Magento\Quote\Model\Quote as CartModel;
use Magento\Quote\Model\Quote\Address\Total as AddressTotalModel;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManager;
use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\Sales\Total\Quote\Shipping;
use Magento\Tax\Model\Sales\Total\Quote\Subtotal;

class Collector extends AbstractTotal
{
    const FEE_AMOUNT_CONFIG = 'sales/palletizing/fee_amount';

    const TOTAL_CODE = 'palletizing_fee';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var StoreManager */
    private $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManager $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    public function collect(
        CartModel $quote,
        ShippingAssignment $shippingAssignment,
        AddressTotalModel $total
    ) {
        $storeId = $quote->getStoreId();
        $items = $shippingAssignment->getItems();
        if (!$items) {
            return $this;
        }

        parent::collect($quote, $shippingAssignment, $total);
        $handlingFees = count($quote->getExtensionAttributes()->getHandlingFeeDetails() ?? []);
        $amount = $this->scopeConfig->getValue(
            self::FEE_AMOUNT_CONFIG,
            ScopeInterface::SCOPE_STORE,
            $quote->getStoreId()
        );

        $totalAmount = $handlingFees * $amount;

        if (!$totalAmount) {
            return $this;
        }

        $total->addTotalAmount(self::TOTAL_CODE, $totalAmount);

        $baseAmount = $this->scopeConfig->getValue(
            self::FEE_AMOUNT_CONFIG,
            ScopeInterface::SCOPE_WEBSITE,
            $this->storeManager->getStore($quote->getStoreId())->getWebsiteId()
        );

        $baseTotal = $handlingFees * $baseAmount;

        $total->addBaseTotalAmount(self::TOTAL_CODE, $baseTotal);
        $quote->setData(self::TOTAL_CODE, $total);

        return $this;
    }

    public function fetch(CartModel $quote, AddressTotalModel $total)
    {
        if ($total->getData(self::TOTAL_CODE . '_amount')) {
            return [
                'code' => 'palletizing_fee',
                'title' => 'HandlingFee Fee',
                'value' => $total->getData(self::TOTAL_CODE . '_amount')
            ];
        }
        return null;
    }

    public function getLabel()
    {
        return __('HandlingFee Fee');
    }
}
