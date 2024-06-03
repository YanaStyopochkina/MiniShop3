ms3.window.CreateVendor = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('ms3_menu_create'),
        width: 600,
        baseParams: {
            action: 'MiniShop3\\Processors\\Settings\\Vendor\\Create',
        }
    })
    ms3.window.CreateVendor.superclass.constructor.call(this, config)
}
Ext.extend(ms3.window.CreateVendor, ms3.window.Default, {
    getFields: function (config) {
        return ms3.config.layout.vendor.window.create;
    },
})
Ext.reg('ms3-window-vendor-create', ms3.window.CreateVendor)

ms3.window.UpdateVendor = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        title: _('ms3_menu_update'),
        baseParams: {
            action: 'MiniShop3\\Processors\\Settings\\Vendor\\Update',
        },
    })
    ms3.window.UpdateVendor.superclass.constructor.call(this, config)
}
Ext.extend(ms3.window.UpdateVendor, ms3.window.CreateVendor, {
    getFields: function (config) {
        return ms3.config.layout.vendor.window.update;
    },
})
Ext.reg('ms3-window-vendor-update', ms3.window.UpdateVendor)
