<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/7/20
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\GiftCard\Model;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface as LayoutProcessor;
use Magento\Checkout\Model\Session\Proxy as CheckoutSession;
use Magento\Quote\Model\Quote;
use SwiftOtter\GiftCard\Model\Repository\GiftCardRepository;

/**
 * This class is optional BUT it suggests a very important concept: passing information to a checkout
 * uiComponent. The videos detail how this exactly works, but we are setting configuration on a uiComponent.
 * Note that you should NOT use checkoutConfig.
 *
 * Class GiftCardCheckoutInitializer
 * @package SwiftOtter\GiftCard\Model
 */
class GiftCardCheckoutInitializer implements LayoutProcessor
{
    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var GiftCardRepository */
    private $giftCardRepository;

    public function __construct(
        CheckoutSession $checkoutSession,
        GiftCardRepository $giftCardRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->giftCardRepository = $giftCardRepository;
    }

    /**
     * @param array $jsLayout
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function process($jsLayout)
    {
        /** @var Quote $quote */
        $quote = $this->checkoutSession->getQuote();

        if (!$quote->getExtensionAttributes()
            || !$quote->getExtensionAttributes()->getGiftCardId()) {
            return $jsLayout;
        }

        $giftCard = $this->giftCardRepository->getById($quote->getExtensionAttributes()->getGiftCardId());
        $jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
            ["children"]["itemsAfter"]["children"]["giftcard"]["config"]["code"] = $giftCard->getCode();

        $jsLayout["components"]["checkout"]["children"]["sidebar"]["children"]["summary"]
            ["children"]["itemsAfter"]["children"]["giftcard"]["config"]["isApplied"] = 1;

        return $jsLayout;
    }
}
