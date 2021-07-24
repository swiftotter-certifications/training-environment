define([
    'mage/utils/wrapper',
], function(wrapper) {
    'use strict';

    let timeout;

    return function(NewCustomerAddress) {
        return function(address) {
            const Output = NewCustomerAddress(address);

            Output.getCacheKey = wrapper.wrapSuper(Output.getCacheKey, function() {
                return this._super() + Date.now();
            })

            return Output;
        }
    }
})
