define([
    'uiComponent',
    'jquery',
    'underscore',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function (Component, $, _, alert, $t) {

    'use strict';

    return Component.extend({
        /**
         * @override
         */
        initialize: function () {
            this._super();
            let self = this;

            window.syncAction = function (url) {
                debugger
                if (!self.isEnabled || !self.isAllowed) {
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    showLoader: true,

                    success: function (data) {
                        debugger
                        if (data.success) {
                            self.processSuccessResponse(data.qty);
                        } else {
                            self.processErrorResponse(data.error_msg)
                        }
                    },

                    error: function (data) {
                        debugger
                        self.processErrorResponse(data.error_msg)
                    }
                });
            };
        },

        /**
         * @param {int} qty
         */
        processSuccessResponse: function (qty) {
            this.updateQtyField(qty);
            alert({
                title: $t('Quantity Successfully Synced From WMS'),
                content: $t('The received quantity (%1) has been set as the value of the <b>Quantity</b> field of the product')
                    .replace('%1', qty)
            });
        },

        /**
         * @param {String} errorMsg
         */
        processErrorResponse: function (errorMsg) {
            alert({
                title: $t('Error While Syncing Quantity From WMS'),
                content: $t(errorMsg ?? 'Undefined error')
            });
        },

        /**
         * @param {int} qty
         */
        updateQtyField: function (qty) {
            debugger
            $("input[name='product[quantity_and_stock_status][qty]']").val(qty);
        }
    });
});
