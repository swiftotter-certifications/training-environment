<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 5/25/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard;

class Constants
{
    const TOTAL_NAME = 'gift_card';

    const OPTION_RECIPIENT_NAME = 'recipient_name';

    const OPTION_RECIPIENT_EMAIL = 'recipient_email';

    const OPTION_AMOUNT = 'amount';

    const OPTION_LIST = [
        self::OPTION_RECIPIENT_EMAIL,
        self::OPTION_RECIPIENT_NAME,
        self::OPTION_AMOUNT
    ];
}