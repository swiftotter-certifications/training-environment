<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 7/19/22
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\Teaching\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use SwiftOtter\Teaching\Service\CountryProvider;

class ShippingEmailInsertion implements LayoutProcessorInterface
{
    private CountryProvider $countryProvider;

    public function __construct(CountryProvider $countryProvider)
    {
        $this->countryProvider = $countryProvider;
    }

    public function process($jsLayout)
    {
        $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["shipping-step"]["children"]["shippingAddress"]["children"]["before-shipping-method-form"]["children"]["shipping-email"]['emails'] = ['joseph@swiftotter.com'];
        $jsLayout["components"]["checkout"]["children"]["steps"]["children"]["shipping-step"]["children"]["shippingAddress"]["children"]["before-shipping-method-form"]["children"]["shipping-email"]['countries'] = $this->countryProvider->get();
        return $jsLayout;
    }
}
