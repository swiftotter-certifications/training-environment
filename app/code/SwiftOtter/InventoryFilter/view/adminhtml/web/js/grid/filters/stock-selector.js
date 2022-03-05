/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'uiLayout',
    'mageUtils',
    'Magento_Ui/js/form/components/group',
    'mage/translate'
], function (_, layout, utils, Group, $t) {
    'use strict';

    return Group.extend({
        defaults: {
            template: 'ui/grid/filters/elements/group',
            isRange: true,
            templates: {
                base: {
                    parent: '${ $.$data.group.name }',
                    provider: '${ $.$data.group.provider }',
                    template: 'ui/grid/filters/field'
                },
                text: {

                },
                ranges: {
                    stock_id: {
                        label: $t('Stock'),
                        dataScope: 'stock_id',
                        component: 'Magento_Ui/js/form/element/select'
                    },
                    from: {
                        label: $t('from'),
                        dataScope: 'from',
                        component: 'Magento_Ui/js/form/element/abstract'
                    },
                    to: {
                        label: $t('to'),
                        dataScope: 'to',
                        component: 'Magento_Ui/js/form/element/abstract'
                    }
                }
            }
        },

        /**
         * Initializes range component.
         *
         * @returns {Range} Chainable.
         */
        initialize: function (config) {
            this._super()
                .initChildren();

            return this;
        },

        /**
         * Creates instances of child components.
         *
         * @returns {Range} Chainable.
         */
        initChildren: function () {
            var children = this.buildChildren();

            layout(children);

            return this;
        },

        /**
         * Creates configuration for the child components.
         *
         * @returns {Object}
         */
        buildChildren: function () {
            var templates   = this.templates,
                typeTmpl    = templates['text'],
                tmpl        = utils.extend({}, templates.base, typeTmpl),
                children    = {};

            _.each(templates.ranges, function (range, key) {
                children[key] = utils.extend({}, tmpl, range);
            });

            if (children.stock_id) {
                children.stock_id.options = this.options;
                children.stock_id.config = {options: this.options};
            }

            return utils.template(children, {
                group: this
            }, true, true);
        },

        /**
         * Clears childrens data.
         *
         * @returns {Range} Chainable.
         */
        clear: function () {
            this.elems.each('clear');

            return this;
        },

        /**
         * Checks if some children has data.
         *
         * @returns {Boolean}
         */
        hasData: function () {
            return this.elems.some('hasData');
        }
    });
});
