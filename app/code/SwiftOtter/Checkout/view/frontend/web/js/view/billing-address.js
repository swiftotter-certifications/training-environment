define([
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/address-list',
    'uiRegistry',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/action/create-billing-address',
    'Magento_Checkout/js/checkout-data',
    'ko',
    'Magento_Customer/js/model/customer',
], function(
    quote,
    addressList,
    registry,
    selectBillingAddress,
    createBillingAddress,
    checkoutData,
    ko,
    customer
) {
    'use strict';

    const shippingExports = [
        'street',
        'city',
        'region_id',
        'postcode',
        'telephone',
        'company'
    ]

    return function(BillingAddress) {
        return BillingAddress.extend({
            hasSetVisibility: false,
            visibilitySetterInterval: null,
            refreshTimeout: null,
            refreshInterval: 5000,
            defaults: {
                isAddressSelectedVisible: false,
                exports: {
                    isAddressSelectedVisible:
                        "${ $.name }.form-fields.street.0:visible, ${ $.name }.form-fields.region_id:visible"

                },
                listens: {
                    "${ $.provider }:${ $.dataScopePrefix }": "refreshAddress",
                    "selectedAddress": "refreshAddress",
                }
            },
            showBillingAddressSummary: ko.computed(function () {
                return quote.billingAddress && quote.billingAddress.customerAddressId ? quote.billingAddress : null;
            }),
            initObservable: function () {
                this._super()
                    .observe(['isOptional', 'isAddressSelectedVisible']);
                return this;
            },
            initialize: function() {
                this._super();

                setTimeout(() => this.refreshInterval = 1000, 5000);

                this.visibilitySetterInterval = setTimeout(this.useAddressForInvoice.bind(this), 500);
                // debugger;
                // this.source[this.dataScopePrefix].subscribe(this.updateAddress);
            },
            refreshAddress: function () {
                if (this.refreshTimeout) {
                    clearTimeout(this.refreshTimeout);
                }

                console.log(this.refreshInterval);
                setTimeout(this.validateAndUpdateAddress.bind(this), this.refreshInterval);
            },
            validateAndUpdateAddress: function() {
                if (!quote.billingAddress()) {
                    return this.updateAddress();
                }

                if (this.selectedAddress()
                    && quote.billingAddress()
                    && this.selectedAddress().customerAddressId === quote.billingAddress().customerAddressId) {
                    return;
                }

                const addressData = this.source.get(this.dataScopePrefix);
                console.log(addressData);
                console.log(quote.billingAddress());


                this.updateAddress();
            },
            insertChild: function(elems, position) {
                this._super(elems, position);

                const address = quote.billingAddress;
                this.useAddressForInvoice();
            },
            useAddressForInvoice: function() {
                if (this.hasSetVisibility) {
                    clearTimeout(this.visibilitySetterInterval);
                }

                this.elems().forEach(child => this.setVisibility(child));

                return true;
            },
            setVisibility(uiComponent) {
                uiComponent.elems().forEach(element => {
                    if (!shippingExports.includes(element.index)) {
                        return;
                    }

                    this.hasSetVisibility = true;

                    element.visible(this.isAddressSelectedVisible());
                    const classes = element.additionalClasses;
                    classes.hidden = !this.isAddressSelectedVisible();
                    element.additionalClasses = classes;
                })
            }
        });
    }
})
