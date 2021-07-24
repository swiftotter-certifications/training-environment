var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/step-navigator': {
                'SwiftOtter_Checkout/js/hash-changer': true
            },
            'Magento_Checkout/js/view/summary/item/details': {
                'SwiftOtter_Checkout/js/view/summary/item/detail-configuration': true
            },
            'Magento_Checkout/js/view/form/element/email': {
                'SwiftOtter_Checkout/js/view/element/email': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'SwiftOtter_Checkout/js/view/billing-address': true
            },
            'Magento_Checkout/js/checkout-data': {
                'SwiftOtter_Checkout/js/checkout-data-merger': true
            },
            'Magento_Checkout/js/model/new-customer-address': {
                'SwiftOtter_Checkout/js/model/new-customer-address-cache-buster': true
            },
            'Magento_Checkout/js/view/shipping': {
                'SwiftOtter_Checkout/js/view/loading-shipping-next': true
            },
            'Magento_Checkout/js/view/payment/list': {
                'SwiftOtter_Checkout/js/view/payment/list-add-loading-indicators': true
            },

            'Magento_Ui/js/form/element/abstract': {
                'SwiftOtter_Checkout/js/view/form-bypass-validation/abstract': true
            }
        }
    }
};
