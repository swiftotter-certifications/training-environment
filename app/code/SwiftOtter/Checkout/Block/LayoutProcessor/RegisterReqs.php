<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/3/21
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Checkout\Block\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use SwiftOtter\Checkout\Service\IsRegisterRequired;

class RegisterReqs implements LayoutProcessorInterface
{
    /** @var CheckoutSession */
    private $checkoutSession;

    /** @var IsRegisterRequired */
    private $isRegisterRequired;

    public function __construct(CheckoutSession $checkoutSession, IsRegisterRequired $isRegisterRequired)
    {
        $this->checkoutSession = $checkoutSession;
        $this->isRegisterRequired = $isRegisterRequired;
    }

    public function process($jsLayout)
    {
        $jsLayout = $this->applyToPaymentStep($jsLayout);
        $jsLayout = $this->applyToShippingStep($jsLayout);

        $payment = $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]["payment"]["children"]["payments-list"]["children"];
        $billingAddressOptional = $this->isRegisterRequired->get();
        foreach ($payment as $type => $values) {
            if (!isset($values['component'])
                || $values['component'] !== 'Magento_Checkout/js/view/billing-address') {
                continue;
            }

            $payment[$type]['isOptional'] = $billingAddressOptional;
        }

        $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]["payment"]["children"]["payments-list"]["children"] = $payment;

        return $jsLayout;
    }

    private function applyToPaymentStep($jsLayout)
    {
        if (!isset($jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]["payment"]["children"]["customer-email"])) {
            return $jsLayout;
        }

        $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]["payment"]["children"]["customer-email"]['registerRequired']
            = $this->isRegisterRequired->get();

        return $jsLayout;
    }

    private function applyToShippingStep($jsLayout)
    {
        if (!isset($jsLayout["components"]["checkout"]["children"]["steps"]["children"]["shipping-step"]["children"]["shippingAddress"]["children"]["customer-email"])) {
            return $jsLayout;
        }

        $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["shipping-step"]["children"]["shippingAddress"]["children"]["customer-email"]["registerRequired"]
            = $this->isRegisterRequired->get();

        return $jsLayout;
    }
}
