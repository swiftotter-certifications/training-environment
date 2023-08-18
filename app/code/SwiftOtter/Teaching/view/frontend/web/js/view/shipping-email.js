define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/payment/place-order-hooks',
    'uiLayout',
    'mageUtils',
    'uiRegistry',
], function(
    $,
    ko,
    Component,
    hooks,
    layout,
    utils,
    registry
) {
    'use strict';

    return Component.extend({
        emails: [],
        visible: true,
        rendererDetails: {},
        rendererComponents: {},
        defaults: {
            listens: {
                '${ $.visibilitySource }:value': 'countryChanged'
            }
        },
        initObservable: function () {
            this._super()
                .observe(['emails', 'visible']);

            hooks.requestModifiers.push(this.addEmail.bind(this))

            if (this.emails().length > 0) {
                this.emails().forEach((value, index) => this.initEmail(index))
            }

            return this;
        },
        addEmail: function(headers, payload) {
            if (!this.visible()) {
                return;
            }

            payload.extension_attributes = payload.extension_attributes || {};
            payload.extension_attributes.shipping_email = this.emails;
        },
        countryChanged: function(country) {
            this.visible(this.countries.indexOf(country) >= 0);
        },
        createEmail: function() {
            const index = this.emails().length;
            this.emails().push('');

            this.initEmail(index);
        },
        initEmail: function(index) {
            const templateData = {
                parent: this,
                name: index, // this cannot contain the parent name
                index: index, // this is passed directly to the child item
                email: this.emails()[index] // this is the initial value
            };

            const rendererComponent = Object.assign(this.rendererDetails, templateData);
            layout([rendererComponent], this);
            this.rendererComponents[index] = rendererComponent;
            this.elems.push(registry.get(name));
        },

        /**
         * I am adding a new capability to the set command: the ability to understand
         * array element indices.
         *
         * Sets provided value as a value of the specified nested property.
         * Triggers changes notifications, if value has mutated.
         *
         * @param {String} path - Path to property.
         * @param {*} value - New value of the property.
         * @returns {Element} Chainable.
         */
        set: function (path, value) {
            /** SwiftOtter BEGIN **/
            let index = undefined,
                match = path.match(/([\[(](\d+)[\])])/);

            if (match) {
                index = match[2];
                path = path.replace(match[1], '');
            }
            /** SwiftOtter END **/

            var data = this.get(path),
                fullData = data, // SwiftOtter added
                diffs;

            if (index) {
                data = data[index];
            }

            diffs = !_.isFunction(data) && !this.isTracked(path) ?
                utils.compare(data, value, path) :
                false;

            // SwiftOtter added switch
            if (index) {
                fullData[index] = value;
            } else {
                fullData = value;
            }

            utils.nested(this, path, fullData);

            if (diffs) {
                this._notifyChanges(diffs);
            }

            return this;
        },
    });
})
