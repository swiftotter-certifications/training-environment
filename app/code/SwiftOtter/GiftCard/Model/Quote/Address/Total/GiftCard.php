<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/13/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Quote\Address\Total;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total as AddressTotal;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use SwiftOtter\GiftCard\Constants;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

class GiftCard extends AbstractTotal
{
    protected $_code = Constants::TOTAL_NAME;

    /** @var GiftCardRepository */
    private $giftCardRepository;

    public function __construct(GiftCardRepository $giftCardRepository)
    {
        $this->giftCardRepository = $giftCardRepository;
    }

    public function fetch(Quote $quote, AddressTotal $total)
    {
        return [
            'code' => $this->getCode(),
            'title' => __('Gift Card'),
            'value' => $total->getData(Constants::TOTAL_NAME . '_amount'),
            'area' => 'footer',
        ];
    }

    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, AddressTotal $total)
    {
        $giftCardId = $quote->getExtensionAttributes()->getGiftCardId();

        /**
         * Note that this will be run twice: the shipping address will have totals BUT the billing address won't.
         *
         * This is an important concept for understanding Magento: shipping address provides the totals that
         * are charged.
         */
        if (!$giftCardId || $shippingAssignment->getShipping()->getAddress()->getData('address_type') !== 'shipping') {
            return $this;
        }

        try {
            $giftCard = $this->giftCardRepository->getById($giftCardId);
        } catch (NoSuchEntityException $ex) {
            return $this;
        }

        if ($giftCard->getCurrentValue() <= 0) {
            return $this;
        }

        $giftCardAmount = $giftCard->getCurrentValue() > array_sum($total->getAllTotalAmounts())
            ? array_sum($total->getAllTotalAmounts())
            : $giftCard->getCurrentValue();

        $baseGiftCardAmount = $giftCard->getCurrentValue() > array_sum($total->getAllBaseTotalAmounts())
            ? array_sum($total->getAllBaseTotalAmounts())
            : $giftCard->getCurrentValue();

        $total->addTotalAmount(Constants::TOTAL_NAME, 0 - $giftCardAmount);
        $total->addBaseTotalAmount(Constants::TOTAL_NAME, 0 - $baseGiftCardAmount);

        $quote->setData(Constants::TOTAL_NAME . '_amount', 0 - $giftCardAmount);
        $quote->setData('base_' . Constants::TOTAL_NAME . '_amount', 0 - $baseGiftCardAmount);

        return $this;
    }
}