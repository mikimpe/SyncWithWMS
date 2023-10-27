define([
    'uiComponent',
    'jquery',
    'underscore',
], function (Component, $, _) {

    'use strict';

    return Component.extend({
        /**
         * Initialize Component
         */
        initialize: function () {
            let self = this;

            this._super();

            window.syncAction = function (url) {
                let isEnabled = self.isEnabled;
                if (!isEnabled) {
                    return;
                }
                $.ajax({
                    url: url,
                    type: 'GET',
                    showLoader: true,

                    success: function (data) {
                        debugger
                    },

                    error: function (jqXHR) {
                        debugger
                    }
                });
            };
        }
    });
});
