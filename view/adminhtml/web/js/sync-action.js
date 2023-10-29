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
                        self.updateQtyField(data.qty);
                        alert({
                            title: $t('Quantity Successfully Synced From WMS'),
                            content: $t('The received quantity (%1) has been set as the value of the <b>Quantity</b> field of the product')
                                .replace('%1', data.qty)
                        });
                    },

                    error: function (jqXHR) {
                        debugger
                        alert({
                            title: $t('Error While Syncing Quantity From WMS'),
                            content: $t('test content')
                        });
                    }
                });
            };
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
