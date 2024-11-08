ms3.window.CustomerAddress = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('ms3_menu_update'),
        width: 600,
        baseParams: {
            action: config.action || 'MiniShop3\\Processors\\Customer\\Address\\Update',
        },
        modal: true,
    });
    ms3.window.CustomerAddress.superclass.constructor.call(this, config);
};
Ext.extend(ms3.window.CustomerAddress, ms3.window.Default, {
    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id'},
            {xtype: 'hidden', name: 'customer_id'},
            {
                layout: 'column',
                border: false,
                anchor: '100%',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_country'),
                                name: 'country',
                                anchor: '100%'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_index'),
                                name: 'index',
                                anchor: '100%'
                            }
                        ]
                    }
                ]
            },
            {
                layout: 'column',
                border: false,
                anchor: '100%',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_region'),
                                name: 'region',
                                anchor: '100%'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_city'),
                                name: 'city',
                                anchor: '100%'
                            }
                        ]
                    },
                ]
            },
            {
                layout: 'column',
                border: false,
                anchor: '100%',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_metro'),
                                name: 'metro',
                                anchor: '100%'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_street'),
                                name: 'street',
                                anchor: '100%'
                            }
                        ]
                    }
                ]
            },
            {
                layout: 'column',
                border: false,
                anchor: '100%',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_building'),
                                name: 'building',
                                anchor: '100%'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_entrance'),
                                name: 'entrance',
                                anchor: '100%'
                            }
                        ]
                    }
                ]
            },
            {
                layout: 'column',
                border: false,
                anchor: '100%',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_floor'),
                                name: 'floor',
                                anchor: '100%'
                            }
                        ]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        defaults: {msgTarget: 'under'},
                        border: false,
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: _('ms3_customer_room'),
                                name: 'room',
                                anchor: '100%'
                            }
                        ]
                    }
                ]
            },
            {
                xtype: 'textarea',
                fieldLabel: _('ms3_customer_comment'),
                name: 'comment',
                height: 100,
                anchor: '100%'
            },
        ];
    },

    getKeys: function () {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function () {
                this.submit()
            }, scope: this
        }];
    },
});
Ext.reg('ms3-window-customer-address-update', ms3.window.CustomerAddress);
