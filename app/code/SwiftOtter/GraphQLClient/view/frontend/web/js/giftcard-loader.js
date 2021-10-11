define([
    'ko',
    'uiComponent',
], function (
    ko,
    Component
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'SwiftOtter_GraphQLClient/giftcard',
            giftcardCode: null,
            message: '',

            tracks: {
                giftcardCode: true,
                message: true
            }
        },

        initialize: function () {
            this._super();

            console.log("initialized");
        },

        update: async function() {
            const query = `
                {
                  giftcards(filter: { code: "${this.giftcardCode}" }) {
                    items {
                      assigned_customer_id
                      code
                      status,
                      recipient_email,
                      recipient_name
                    }
                    total_count
                  }
                }
            `;

            fetch(this.url, {
                method: 'POST',
                credentials: 'include',
                'headers': {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({"query": query })
            }).then(response => response.json())
                .then(response => this.processResult(response))
        },

        processResult(response) {
            if (!response.hasOwnProperty('data')
                || !response.data.hasOwnProperty('giftcards')
                || !response.data.giftcards.hasOwnProperty('total_count')
                || !response.data.giftcards.hasOwnProperty('items')
                || !response.data.giftcards.items.length) {
                this.message = 'This does not match any giftcard.';
                return;
            }

            this.message = `${this.giftcardCode} is a valid giftcard.`;
        }
    });
});
