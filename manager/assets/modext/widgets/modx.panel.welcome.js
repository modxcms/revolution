/**
 * @class MODx.panel.Welcome
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-welcome
 */
MODx.panel.Welcome = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-welcome'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+MODx.config.site_name+'</h2>'
            ,id: 'modx-welcome-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            applyTo: 'modx-dashboard'
            ,border: false
        }]
    });
    MODx.panel.Welcome.superclass.constructor.call(this,config);
    MODx.fireEvent('ready');
};
Ext.extend(MODx.panel.Welcome,MODx.FormPanel);
Ext.reg('modx-panel-welcome',MODx.panel.Welcome);