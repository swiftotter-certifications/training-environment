<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem as CartItem;
use SwiftOtter\GiftCard\Constants;

class CartDetails implements ArgumentInterface
{
    public function getCardDetails(CartItem $item)
    {
        $codes = [
            (string)__('Recipient name') => Constants::OPTION_RECIPIENT_NAME,
            (string)__('Recipient email') => Constants::OPTION_RECIPIENT_EMAIL
        ];

        $output = array_map(function($value) use ($item) {
            if ($item->getOptionByCode($value)) {
                return $item->getOptionByCode($value)->getValue();
            }

            return '';
        }, $codes);

        return array_filter($output);
    }
}