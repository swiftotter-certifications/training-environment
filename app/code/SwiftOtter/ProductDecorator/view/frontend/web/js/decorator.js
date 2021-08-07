define([
    'uiCollection',
    'ko',
    'uiLayout'
], function(Component, ko, layout) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'SwiftOtter_ProductDecorator/decorator',
            print_method_id: null,
            location_id: null,
            colors: null,
            displayText: null,
            qty: null,

            imports: {
                qty: 'decoratorQty:qty'
            },

            tracks: {
                print_method_id: true,
                location_id: true,
                colors: true,
                displayText: true,
                qty: true
            }
        },

        initObservable: function() {
            this._super();
            this.printMethods = ko.pureComputed(() => {
                if (!this.location_id
                    || !this.data.location_print_methods.hasOwnProperty(this.location_id)) {
                    return {};
                }

                const printMethodIds = this.data.location_print_methods[this.location_id];

                return this.data.print_methods.filter((details) => printMethodIds.indexOf(details.id) >= 0);
            });

            this.priceType = ko.pureComputed(() => {
                if (!this.print_method_id) {
                    return {};
                }

                return this.data.print_methods.find((details) => this.print_method_id === details.id).price_type;
            })

            this.maxColors = ko.pureComputed(() => {
                if (!this.priceType()
                    || !this.data.color_limiter.hasOwnProperty(this.priceType())) {
                    return 0;
                }

                const options = this.data.color_limiter[this.priceType()]
                    .sort((a, b) => a.min_tier - b.min_tier)
                    .filter(({min_tier}) => min_tier < this.qty);

                if (!options.length) {
                    return 0;
                }

                return options.slice(-1).pop().max_colors;
            });

            this.request = ko.pureComputed(() => {
                return {
                    products: [
                        { sku: this.data.sku, quantity: this.qty}
                    ],
                    locations: [
                        { location_id: this.location_id, print_method_id: this.print_method_id, colors: this.colors, display_text: this.displayText}
                    ]
                }
            });

            return this;
        }
    })
})
