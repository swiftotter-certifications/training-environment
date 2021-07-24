define([
    'ko',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/action/check-email-availability',
    'Magento_Checkout/js/model/payment/place-order-hooks',
    'jquery'
], function(ko, checkoutData, quote, checkEmailAvailability, hooks, $) {
    'use strict';

    return function(Email) {
        return Email.extend({
            checkDelay: 500,
            defaults: {
                isLoginButtonVisible: false,
                password: ''
            },

            initObservable: function () {
                this._super()
                    .observe(['isLoginButtonVisible', 'password']);

                this.passwordRequired = ko.computed(() => {console.log(this.isLoginButtonVisible()); return this.isLoginButtonVisible() ? 'field' : 'field required';});

                hooks.requestModifiers.push(this.addPassword.bind(this));

                return this;
            },

            addPassword: function(headers, payload) {
                if (this.isLoginButtonVisible()
                    || !payload.hasOwnProperty('paymentMethod')
                    || !this.password()) {
                    return;
                }

                if (!payload.paymentMethod.hasOwnProperty('extensionAttributes')) {
                    payload.paymentMethod.extensionAttributes = {};
                }

                payload.paymentMethod.extensionAttributes.registerPassword = this.password();
            },

            resolveInitialPasswordVisibility: function() {
                return this.registerRequired || this._super();
            },

            loginIsVisible: function() {
                return false;
            },

            /**
             * Callback on changing email property
             */
            emailHasChanged: function () {
                var self = this;

                clearTimeout(this.emailCheckTimeout);

                if (self.validateEmail()) {
                    quote.guestEmail = self.email();
                    checkoutData.setValidatedEmailValue(self.email());
                }

                this.emailCheckTimeout = setTimeout(function () {
                    if (self.validateEmail()) {
                        self.checkEmailAvailability();
                    }
                }, self.checkDelay);

                checkoutData.setInputFieldEmailValue(self.email());
            },

            /**
             * Check email existing.
             */
            checkEmailAvailability: function () {
                this.validateRequest();
                this.isEmailCheckComplete = $.Deferred();
                this.isLoading(true);
                this.checkRequest = checkEmailAvailability(this.isEmailCheckComplete, this.email());

                $.when(this.isEmailCheckComplete).done(function () {
                    this.isPasswordVisible(this.registerRequired);
                    this.isLoginButtonVisible(false);
                    checkoutData.setCheckedEmailValue('');
                }.bind(this)).fail(function () {
                    this.isPasswordVisible(true);
                    this.isLoginButtonVisible(true);
                    checkoutData.setCheckedEmailValue(this.email());
                }.bind(this)).always(function () {
                    this.isLoading(false);
                }.bind(this));
            },
        });
    }
})
