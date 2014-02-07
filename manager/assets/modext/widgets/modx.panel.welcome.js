/**
 * @class MODx.panel.Welcome
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-welcome
 */
MODx.panel.Welcome = function(config) {
    dashboardName = config.dashboard.id == 1 ? MODx.config.site_name : MODx.config.site_name+' - '+config.dashboard.name;
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-welcome'
        ,cls: 'container'
        ,baseCls: 'modx-formpanel'
        ,layout: 'form'
        ,defaults: {
            collapsible: false
            ,autoHeight: true
        }
        ,items: [{
            html: '<h2>'+dashboardName+'</h2>'
            ,id: 'modx-welcome-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            applyTo: 'modx-dashboard'
            ,border: false
        }]
    });
    MODx.panel.Welcome.superclass.constructor.call(this,config);
    this.setup();
};
Ext.extend(MODx.panel.Welcome, MODx.Panel,{
    setup: function() {
        if (this.config.dashboard && this.config.dashboard.hide_trees) {
            Ext.getCmp('modx-layout').hideLeftbar(false);
        }
        MODx.fireEvent('ready');
    }
});
Ext.reg('modx-panel-welcome', MODx.panel.Welcome);
