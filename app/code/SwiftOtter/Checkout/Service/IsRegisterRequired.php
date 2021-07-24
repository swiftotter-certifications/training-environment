<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Service;

use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Model\Session;
use Magento\Downloadable\Model\Product\Type as DownloadableProductType;
use Magento\Quote\Model\Quote\Item as CartItem;

class IsRegisterRequired
{
    /** @var Session */
    private $checkoutSession;

    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    public function get(): bool
    {
        /** @var CartItem $item */
        foreach ($this->checkoutSession->getQuote()->getAllItems() as $item) {
            if ($item->getProductType() === DownloadableProductType::TYPE_DOWNLOADABLE
                || $item->getProductType() === Type::TYPE_VIRTUAL) {
                return true;
            }

            // TODO; add single-option take
        }

        return false;
    }
}
