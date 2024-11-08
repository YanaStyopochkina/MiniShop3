ms3.window.UpdateCustomer = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('ms3_menu_update'),
        width: 750,
        baseParams: {
            action: 'MiniShop3\\Processors\\Customer\\Update',
        },
    });
    ms3.window.UpdateCustomer.superclass.constructor.call(this, config);
};
Ext.extend(ms3.window.UpdateCustomer, ms3.window.Default, {

    getFields: function (config) {
        return {
            xtype: 'modx-tabs',
            activeTab: config.activeTab || 0,
            bodyStyle: {background: 'transparent'},
            deferredRender: false,
            autoHeight: true,
            stateful: true,
            stateId: 'ms3-window-customer-update',
            stateEvents: ['tabchange'],
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            },
            items: this.getTabs(config)
        };
    },

    getTabs: function (config) {
        const tabs = [
            {
                title: _('ms3_customer'),
                hideMode: 'offsets',
                defaults: {msgTarget: 'under', border: false},
                items: this.getCustomerFields(config)
            },
            {
                xtype: 'ms3-grid-customer-addresses',
                title: _('ms3_customer_addresses'),
                customer_id: config.record.id
            }
        ];

        return tabs;
    },

    getCustomerFields: function (config) {
        const all = {
            first_name: {},
            last_name: {},
            phone: {},
            email: {},
        };
        const fields = []
        const tmp = [];
        for (let i = 0; i < ms3.config['customer_window_fields'].length; i++) {
            const field = ms3.config['customer_window_fields'][i];
            if (all[field]) {
                Ext.applyIf(all[field], {
                    xtype: 'textfield',
                    name: field,
                    fieldLabel: _('ms3_' + field)
                });
                all[field].anchor = '100%';
                tmp.push(all[field]);
            }
        }

        const addx = function (w1, w2) {
            if (!w1) {
                w1 = .5;
            }
            if (!w2) {
                w2 = .5;
            }
            return {
                layout: 'column',
                defaults: {msgTarget: 'under', border: false},
                items: [
                    {columnWidth: w1, layout: 'form', items: []},
                    {columnWidth: w2, layout: 'form', items: []}
                ]
            };
        };

        let n;
        if (tmp.length > 0) {
            for (i = 0; i < tmp.length; i++) {
                if (i % 2 === 0) {
                    fields.push(addx());
                }
                if (i <= 1) {
                    n = 0;
                } else {
                    n = Math.floor(i / 2);
                }
                fields[n].items[i % 2].items.push(tmp[i]);
            }
        }

        fields.unshift({
            xtype: 'hidden',
            name: 'id'
        })

        return fields;
    },

    getKeys: function () {
        return {
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }
    },

});
Ext.reg('ms3-window-customer-update', ms3.window.UpdateCustomer);
