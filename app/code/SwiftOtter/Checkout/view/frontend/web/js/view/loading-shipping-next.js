define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/step-navigator',
], function(
    $,
    ko,
    quote,
    checkoutDataResolver,
    checkoutData,
    registry,
    setShippingInformationAction,
    stepNavigator
) {
    'use strict';

    return function(Shipping) {
        return Shipping.extend({
            isLoading: false,
            defaults: {
                isLoading: false,
                shippingText: 'Next'
            },
            initObservable: function () {
                this._super()
                    .observe(['isLoading']);

                this.shippingText = ko.computed(() => this.isLoading() ? 'Loading...' : 'Next');

                return this;
            },
            setShippingInformation: function () {
                if (this.validateShippingInformation()) {
                    quote.billingAddress(null);
                    checkoutDataResolver.resolveBillingAddress();
                    registry.async('checkoutProvider')(function (checkoutProvider) {
                        var shippingAddressData = checkoutData.getShippingAddressFromData();

                        if (shippingAddressData) {
                            checkoutProvider.set(
                                'shippingAddress',
                                $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                            );
                        }
                    });

                    this.isLoading = true;
                    setShippingInformationAction().done(() => {
                            stepNavigator.next();
                            this.isLoading = false;
                        }
                    );
                }
            },
        });
    }
})
