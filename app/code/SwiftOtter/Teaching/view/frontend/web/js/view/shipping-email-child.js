define([
    'jquery',
    'ko',
    'uiComponent'
], function(
    $,
    ko,
    Component,
) {
    'use strict';

    return Component.extend({
        email: '',
        defaults: {
            exports: {
                email: '${ $.parentName }:emails[${ $.index }]'
            }
        },
        initObservable: function () {
            this._super()
                .observe(['email']);

            return this;
        }
    });
})
