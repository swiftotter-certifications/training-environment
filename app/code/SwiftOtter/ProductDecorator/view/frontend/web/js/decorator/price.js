define([
    'uiComponent',
    'ko',
    'uiLayout'
], function(Component, ko, layout) {
    'use strict';

    return Component.extend({
        defaults: {
            displayPrice: null,
            canDisplay: false,
            imports: {
                "request": "decorator:request",
                "url": "decorator:data.price_url"
            },
            listens: {
                "decorator:print_method_id": "beginUpdatePrice",
                "decorator:location_id": "beginUpdatePrice",
                "decorator:colors": "beginUpdatePrice"
            },
            tracks: {
                qty: true,
                displayPrice: true,
                canDisplay: true
            }
        },
        initObservable: function() {
            this._super();

            this.isLoading = false;

            return this;
        },

        initialize: function () {
            this._super();
            this.canDisplay = true;
            this.delay = undefined;

            return this;
        },

        beginUpdatePrice: function() {
            clearTimeout(this.delay);
            this.delay = setTimeout(this.update.bind(this), 1000);
        },

        update: async function() {
            if (this.isLoading) {
                return;
            }

            const response = await this.fetch();
            if (response.hasOwnProperty('formatted_unit_price')) {
                this.displayPrice = "<span class='price'>" + response.formatted_unit_price + "</span>";
            }
        },

        fetch: async function() {
            this.isLoading = true;
            const data = {
                price_request: this.request
            };

            const response = await fetch(this.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            this.isLoading = false;
            return response.json();
        }
    })
})
