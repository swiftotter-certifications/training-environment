define([
    'Magento_Checkout/js/model/resource-url-manager'
  ], function (urlManager) {
    'use strict';

    return {
      getUrlForGiftCardApplication: function (quote) {
        var params = urlManager.getCheckoutMethod() == 'guest' ?
          {
            cartId: quote.getQuoteId()
          } : {},
          urls = {
            'guest': '/guest-carts/:cartId/gift-card',
            'customer': '/carts/mine/gift-card'
          };

        return urlManager.getUrl(urls, params);
      }
    };
  }
);
