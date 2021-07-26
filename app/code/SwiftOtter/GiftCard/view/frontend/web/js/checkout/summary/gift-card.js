define([
  'Magento_Checkout/js/view/summary/abstract-total',
  'Magento_Checkout/js/model/quote'
], function (Component, quote) {
  'use strict';

  return Component.extend({
    defaults: {
      template: 'SwiftOtter_GiftCard/checkout/summary/gift-card'
    },

    isDisplayed: function () {
      return this.isFullMode();
    },

    getPureValue: function () {
      var totals = quote.getTotals()();

      return totals.total_segments.reduce(function(result, total) {
        if (total.code === "giftcard") {
          return total.value;
        }

        return result;
      }, 0);
    },

    getValue: function () {
      return this.getFormattedPrice(this.getPureValue());
    }
  });
});
