define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/payment/place-order-hooks'
], function(
    $,
    ko,
    Component,
    hooks
) {
    'use strict';

    return Component.extend({
        email: '',
        visible: true,
        defaults: {
            listens: {
                '${ $.visibilitySource }:value': 'countryChanged'
            }
        },
        initObservable: function () {
            this._super()
                .observe(['email', 'visible']);

            hooks.requestModifiers.push(this.addEmail)

            return this;
        },
        addEmail: function(headers, payload) {
            payload.extension_attributes = payload.extension_attributes || {};
            payload.extension_attributes.shipping_email = this.email;
        },
        countryChanged: function(country) {
            this.visible(this.countries.indexOf(country) >= 0);
        }
    });
})
