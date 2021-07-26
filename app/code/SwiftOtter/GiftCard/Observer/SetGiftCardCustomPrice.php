<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use SwiftOtter\GiftCard\Constants;
use SwiftOtter\GiftCard\Model\Type\GiftCard;

class SetGiftCardCustomPrice implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getData('quote');

        /** @var QuoteItem $item */
        foreach ($quote->getAllAddresses() as $address) {
            foreach ($address->getAllItems() as $item) {
                if ($item->getProductType() !== GiftCard::TYPE_CODE
                    || !$item->getOptionByCode(Constants::OPTION_AMOUNT)
                    || !$item->getOptionByCode(Constants::OPTION_AMOUNT)->getValue()) {
                    continue;
                }

                $item->setCustomPrice($item->getOptionByCode(Constants::OPTION_AMOUNT)->getValue());
                $item->setOriginalCustomPrice($item->getOptionByCode(Constants::OPTION_AMOUNT)->getValue());
            }
        }
    }
}