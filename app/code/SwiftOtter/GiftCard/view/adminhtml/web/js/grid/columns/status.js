define([
    'underscore',
    'Magento_Ui/js/grid/columns/select'
], function (_, Select) {
    'use strict';

    return Select.extend({
        isBold: (row) => row.status == 1,
        isStrike: (row) => row.status == 2
    });
});
