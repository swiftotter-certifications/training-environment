define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'SwiftOtter_Palletizing/grid/cells/text',
        },

        initialize: function () {
            this._super();

            debugger;
            return this;
        },
    });
});
