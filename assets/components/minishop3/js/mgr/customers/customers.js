ms3.page.Customers = function (config) {
    config = config || {};
    Ext.apply(config, {
        formpanel: 'ms3-panel-customers',
        cls: 'container',
        components: [{
            xtype: 'ms3-panel-customers'
        }]
    });
    ms3.page.Customers.superclass.constructor.call(this, config);
};
Ext.extend(ms3.page.Customers, MODx.Component, {});
Ext.reg('ms3-page-customers', ms3.page.Customers);
