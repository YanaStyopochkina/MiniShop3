let SettingsVendorWindowCreateFields = []
let SettingsVendorWindowUpdateFields = []

setTimeout(() => {
    MODx.Ajax.request({
        url: ms3.config.connector_url,
        params: {
            action: 'MiniShop3\\Processors\\Config\\Read',
            config: 'settings/vendor/window',
        },
        listeners: {
            success: {
                fn: function (response) {
                    SettingsVendorWindowCreateFields = Object.assign(SettingsVendorWindowCreateFields, response.object.createLayout)
                    SettingsVendorWindowUpdateFields = Object.assign(SettingsVendorWindowUpdateFields, response.object.updateLayout)
                }
            },
            failure: {
                fn: function (response) {
                    MODx.msg.alert(_('error'), response.message)
                }
            },
        }
    })
}, 400)

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
        return SettingsVendorWindowCreateFields
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
        return SettingsVendorWindowUpdateFields
    },
})
Ext.reg('ms3-window-vendor-update', ms3.window.UpdateVendor)
