<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/2/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use SwiftOtter\GiftCard\Model\GiftCard;

class Status implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            [
                'label' => __('Active'),
                'value' => GiftCard::STATUS_ACTIVE
            ],
            [
                'label' => __('Not active'),
                'value' => GiftCard::STATUS_USED
            ],
        ];
    }
}