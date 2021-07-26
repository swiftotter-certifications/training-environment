<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/15/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\TotalsExtensionFactory;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Quote\Model\Cart\CartTotalRepository;

class AddGiftCardAmountToTotalsRepository
{
    /** @var TotalsExtensionFactory */
    private $extensionFactory;

    /** @var CartRepositoryInterface */
    private $cartRepository;

    public function __construct(
        TotalsExtensionFactory $extensionFactory,
        CartRepositoryInterface $cartRepository
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->cartRepository = $cartRepository;
    }

    public function afterGet(
        CartTotalRepository $subject,
        TotalsInterface $result,
        $cartId
    ) {
        if ($result->getExtensionAttributes() === null) {
            $extensionAttributes = $this->extensionFactory->create();
            $result->setExtensionAttributes($extensionAttributes);
        }

        $cart = $this->cartRepository->getActive($cartId);


    }
}