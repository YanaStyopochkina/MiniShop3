ms3.grid.Customers = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'ms3-grid-customers';
    }
    config.disableContextMenuAction = true;

    Ext.applyIf(config, {
        baseParams: {
            action: 'MiniShop3\\Processors\\Customer\\GetList',
            sort: 'id',
            dir: 'desc',
        },
        multi_select: true,
        changed: false,
        stateful: true,
        stateId: config.id,
    });
    ms3.grid.Customers.superclass.constructor.call(this, config);
};
Ext.extend(ms3.grid.Customers, ms3.grid.Default, {

    getFields: function () {
        return ms3.config['customer_grid_fields'];
    },

    getColumns: function () {
        const all = {
            id: {width: 35},
            first_name: {width: 100},
            last_name: {width: 100},
            email: {width: 100},
            phone: {width: 100},
            actions: {width: 75, id: 'actions', renderer: ms3.utils.renderActions, sortable: false},
        };

        const fields = this.getFields();
        const columns = [];
        for (let i = 0; i < fields.length; i++) {
            const field = fields[i];
            Ext.applyIf(all[field], {
                header: _('ms3_' + field),
                dataIndex: field,
                sortable: true,
            });
            columns.push(all[field]);
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
                this.updateCustomer(grid, e, row);
            },
            afterrender: function (grid) {
                const params = ms3.utils.Hash.get();
                const customer = params['customer'] || '';
                if (customer) {
                    this.update(grid, Ext.EventObject, {data: {id: customer}});
                }
            },
        };
    },

    action: function (method) {
        const ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'MiniShop3\\Processors\\Customer\\Multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        //noinspection JSUnresolvedFunction
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    }, scope: this
                },
            }
        })
    },

    update: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        const id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'MiniShop3\\Processors\\Customer\\Get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        let w = Ext.getCmp('ms3-window-customer-update');
                        if (w) {
                            w.close();
                        }

                        w = MODx.load({
                            xtype: 'ms3-window-customer-update',
                            id: 'ms3-window-customer-update',
                            record: r.object,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                },
                                hide: {
                                    fn: function () {
                                        ms3.utils.Hash.remove('customer');
                                        if (ms3.grid.Customers.changed === true) {
                                            Ext.getCmp('ms3-grid-customers').getStore().reload();
                                            ms3.grid.Customers.changed = false;
                                        }
                                    }
                                },
                                afterrender: function () {
                                    ms3.utils.Hash.add('customer', r.object['id']);
                                }
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

    remove: function () {
        const ids = this._getSelectedIds();

        Ext.MessageBox.confirm(
            _('ms3_menu_remove_title'),
            ids.length > 1
                ? _('ms3_menu_remove_multiple_confirm')
                : _('ms3_menu_remove_confirm'),
            function (val) {
                if (val === 'yes') {
                    this.action('Remove');
                }
            },
            this
        );
    },

});
Ext.reg('ms3-grid-customers', ms3.grid.Customers);
