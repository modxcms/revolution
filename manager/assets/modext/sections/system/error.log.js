/**
 * Loads the error log page
 * 
 * @class MODx.page.ErrorLog
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-event
 */
MODx.page.ErrorLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-error-log'
            ,record: config.record || {}
        }]
    });
    MODx.page.ErrorLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ErrorLog,MODx.Component);
Ext.reg('modx-page-error-log',MODx.page.ErrorLog);
