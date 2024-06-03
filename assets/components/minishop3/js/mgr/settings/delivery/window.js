ms3.window.CreateDelivery = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('ms3_delivery'),
        width: 600,
        baseParams: {
            action: 'MiniShop3\\Processors\\Settings\\Delivery\\Create',
        },
    });
    ms3.window.CreateDelivery.superclass.constructor.call(this, config);
};
Ext.extend(ms3.window.CreateDelivery, ms3.window.Default, {
    getFields: function (config) {
        return ms3.config.layout.delivery.window.create;
    },
});
Ext.reg('ms3-window-delivery-create', ms3.window.CreateDelivery);


ms3.window.UpdateDelivery = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        baseParams: {
            action: 'MiniShop3\\Processors\\Settings\\Delivery\\Update',
        },
        bodyCssClass: 'tabs',
    });
    ms3.window.UpdateDelivery.superclass.constructor.call(this, config);
};
Ext.extend(ms3.window.UpdateDelivery, ms3.window.CreateDelivery, {
    getFields: function (config) {
        return [{
            xtype: 'modx-tabs',
            items: [
                {
                    title: _('ms3_delivery'),
                    layout: 'form',
                    items: ms3.config.layout.delivery.window.update.info,
                },
                {
                    title: _('ms3_settings'),
                    layout: 'form',
                    items: ms3.config.layout.delivery.window.update.settings,
                },
                {
                    title: _('ms3_payments'),
                    items: [{
                        xtype: 'ms3-grid-delivery-payments',
                        record: config.record,
                    }]
                }
            ]
        }];
    }

});
Ext.reg('ms3-window-delivery-update', ms3.window.UpdateDelivery);
