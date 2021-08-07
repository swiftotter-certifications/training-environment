define([
    'uiComponent',
    'ko',
    'uiLayout'
], function(Component, ko, layout) {
    'use strict';

    return Component.extend({
        defaults: {
            qty: 1,
            canDisplay: false,
            tracks: {
                qty: true,
                canDisplay: true
            }
        },
        initialize: function () {
            this._super();
            this.canDisplay = true;

            return this;
        },
    })
})
