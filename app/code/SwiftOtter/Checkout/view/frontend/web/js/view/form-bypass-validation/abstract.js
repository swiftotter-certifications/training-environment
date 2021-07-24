define([
    'uiRegistry'
], function(uiRegistry) {
    'use strict';

    return function(FormElement) {
        return FormElement.extend({
            validate: function() {
                if (!this.visible() || !this.parentVisible()) {
                    return {
                        valid: true,
                        target: this
                    }
                }

                return this._super();
            },
            parentVisible: function() {
                const parent = uiRegistry.get(this.parentName);

                if (!parent || !parent.hasOwnProperty('visible')) {
                    return true;
                }

                return parent.visible();
            }
        });
    }
})
