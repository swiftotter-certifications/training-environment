define(['jquery'], function($) {
    'use strict';

    return function(rules) {
        rules['validate-order-shipping-email'] = {
            handler: (value, element) => {
                return !value
                    || !value.match(/\S+[+]\S+@/g);
            },
            message: $.mage.__('We do not allow the "+" syntax in email addresses.')
        }

        return rules;
    }
})
