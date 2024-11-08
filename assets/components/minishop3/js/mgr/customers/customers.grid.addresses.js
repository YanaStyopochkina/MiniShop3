ms3.grid.CustomerAddresses = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ms3-grid-customer-addresses';
    }
    config.disableContextMenuAction = true;
    Ext.applyIf(config, {
        baseParams: {
            action: 'MiniShop3\\Processors\\Customer\\Address\\GetList',
            customer_id: config.customer_id,
        },
        cls: 'ms3-grid',
        multi_select: false,
        stateful: true,
        stateId: config.id,
        pageSize: Math.round(MODx.config['default_per_page'] / 2),
    });
    ms3.grid.CustomerAddresses.superclass.constructor.call(this, config);
};
Ext.extend(ms3.grid.CustomerAddresses, ms3.grid.Default, {

    getFields: function () {
        return ms3.config['customer_address_grid_fields'];
    },

    getColumns: function () {
        const fields = {
            id: {hidden: true, sortable: true, width: 40},
            country: {header: _('ms3_customer_country'), width: 50},
            index: {header: _('ms3_customer_index'), width: 50},
            region: {header: _('ms3_customer_region'), width: 50},
            city: {header: _('ms3_customer_city'), width: 50},
            street: {header: _('ms3_customer_street'), width: 50},
            building: {header: _('ms3_customer_building'), width: 50},
            entrance: {header: _('ms3_customer_entrance'), width: 50},
            floor: {header: _('ms3_customer_floor'), width: 50},
            room: {header: _('ms3_customer_room'), width: 50},
            actions: {width: 75, id: 'actions', renderer: ms3.utils.renderActions, sortable: false},
        };

        const columns = [];
        for (let i = 0; i < ms3.config['customer_address_grid_fields'].length; i++) {
            const field = ms3.config['customer_address_grid_fields'][i];
            if (fields[field]) {
                Ext.applyIf(fields[field], {
                    header: _('ms3_' + field),
                    dataIndex: field
                });
                columns.push(fields[field]);
            }
        }

        return columns;
    },

    getTopBar: function () {
        return [];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                const row = grid.store.getAt(rowIndex);
                this.updateCustomerAddress(grid, e, row);
            }
        };
    },

    updateAddress: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        console.log(this.menu.record)
        const id = this.menu.record.id;

        MODx.Ajax.request({
            url: ms3.config.connector_url,
            params: {
                action: 'MiniShop3\\Processors\\Customer\\Address\\Get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        let w = Ext.getCmp('ms3-window-customer-address-update');
                        if (w) {
                            w.close();
                        }

                        r.object.customer_id = this.config.customer_id;
                        w = MODx.load({
                            xtype: 'ms3-window-customer-address-update',
                            id: 'ms3-window-customer-address-update',
                            record: r.object,
                            action: 'MiniShop3\\Processors\\Customer\\Address\\Update',
                            listeners: {
                                success: {
                                    fn: function () {
                                        ms3.grid.Customers.changed = true;
                                        this.refresh();
                                    }, scope: this
                                },
                            }
                        });
                        w.fp.getForm().reset();
                        w.fp.getForm().setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeAddress: function () {
        if (!this.menu.record) {
            return;
        }

        MODx.msg.confirm({
            title: _('ms3_menu_remove'),
            text: _('ms3_menu_remove_confirm'),
            url: ms3.config.connector_url,
            params: {
                action: 'MiniShop3\\Processors\\Customer\\Address\\Remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function () {
                        ms3.grid.Customers.changed = true;
                        this.refresh();
                    }, scope: this
                }
            }
        });
    }
});
Ext.reg('ms3-grid-customer-addresses', ms3.grid.CustomerAddresses);
