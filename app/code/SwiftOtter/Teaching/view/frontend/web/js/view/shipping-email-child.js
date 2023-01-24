define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract'
], function(
    $,
    ko,
    Component,
) {
    'use strict';

    return Component.extend({
        defaults: {
            exports: {
                value: '${ $.parentName }:emails[${ $.index }]'
            },
            listens: {
                'checkoutProvider:shippingAddress.data.validate': 'validate'
            }
        },
        validation: {
            'validate-order-shipping-email': true
        },
        getTemplate: function() {
            return this.template;
        }
    });
})
