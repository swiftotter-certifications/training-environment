define([], function() {
    'use strict';

    return function(Details) {
        return Details.extend({
            filter: function(options) {
                let output = JSON.parse(options);
                return output.filter(({label, value}) => label !== 'Links');
            }
        });
    }
})
