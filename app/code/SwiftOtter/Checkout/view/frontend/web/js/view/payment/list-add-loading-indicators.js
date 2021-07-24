define([
    'Magento_Checkout/js/model/payment/place-order-hooks'
], function(
    hooks
) {
    'use strict'

    return function(PaymentList) {
        return PaymentList.extend({
            defaults: {
                isLoading: false
            },

            initObservable: function() {
                this._super().
                    observe(['isLoading']);

                hooks.requestModifiers.push(this.assignIsLoading.bind(this));
                hooks.afterRequestListeners.push(this.cleanIsLoading.bind(this));

                return this;
            },

            assignIsLoading: function(headers, payload) {
                if (!payload.hasOwnProperty('billingAddress')) {
                    return;
                }

                this.isLoading(true);
            },

            cleanIsLoading: function() {
                this.isLoading(false);
            }
        });
    }
})
