define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
], function(wrapper, quote) {
    'use strict';

    let timeout;

    return function(CheckoutData) {
        CheckoutData.getNewCustomerBillingAddress = wrapper.wrapSuper(CheckoutData.getNewCustomerBillingAddress, function() {
            const data = this._super();
            const toMerge = this.getBillingAddressFromData();

            if (toMerge === null || toMerge === undefined) {
                return data;
            }

            let output = Object.assign({...toMerge}, data);
            if (output.hasOwnProperty('country_id')
                && output.country_id === "US"
                && toMerge.hasOwnProperty('country_id')
                && toMerge.country_id !== "US") {
                output.country_id = toMerge.country_id;
            }

            return output;
        })

        CheckoutData.setShippingAddressFromData = wrapper.wrapSuper(CheckoutData.setShippingAddressFromData, function(data) {
            this._super();

            if (timeout) {
                clearTimeout(timeout);
            }

            timeout = setTimeout(() => {
                const address = quote.shippingAddress();

                for (const [key, value] of Object.entries(data)) {
                    if (!value) {
                        continue;
                    }

                    address[key] = value;
                }

                quote.shippingAddress(address);
            }, 500);
        })

        return CheckoutData;
    }
})
