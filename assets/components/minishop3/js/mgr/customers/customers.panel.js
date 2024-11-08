ms3.panel.Customers = function (config) {
    config = config || {};

    Ext.apply(config, {
        cls: 'container',
        items: [{
            html: '<h2>' + _('ms3_header') + ' :: ' + _('ms3_customers') + '</h2>',
            cls: 'modx-page-header',
        }, {
            xtype: 'modx-tabs',
            id: 'ms3-customers-tabs',
            stateful: true,
            stateId: 'ms3-customers-tabs',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            deferredRender: false,
            items: [{
                title: _('ms3_customers'),
                layout: 'anchor',
                items: [
                    {
                        xtype: 'ms3-grid-customers',
                        id: 'ms3-grid-customers',
                    }
                ],
            }]
        }]
    });
    ms3.panel.Customers.superclass.constructor.call(this, config);
};
Ext.extend(ms3.panel.Customers, MODx.Panel);
Ext.reg('ms3-panel-customers', ms3.panel.Customers);
