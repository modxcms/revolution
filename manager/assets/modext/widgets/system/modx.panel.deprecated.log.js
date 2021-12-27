/**
 * Loads the DeprecatedLog panel
 *
 * @class MODx.panel.DeprecatedLog
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-error-log
 */
MODx.panel.DeprecatedLog = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,id: 'modx-panel-deprecated-log'
        ,cls: 'container'
        ,baseParams: {
            action: 'System/DeprecatedLog/Clear'
        }
        // ,layout: 'form' // unnecessary and creates a wrong box shadow
        ,items: [{
            html: _('deprecated_log')
            ,id: 'modx-deprecated-log-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('deprecated_log')
            ,layout: 'form'
            ,hideLabels: true
            ,autoHeight: true
            ,border: true
            ,items: [{
                html: '<p>'+_('deprecated_log_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-deprecated-log'
                ,id: 'modx-grid-deprecated-log'
                ,border: false
                ,cls:'main-wrapper'
            }]
        }])]
    });
    MODx.panel.DeprecatedLog.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.DeprecatedLog,MODx.FormPanel);
Ext.reg('modx-panel-deprecated-log',MODx.panel.DeprecatedLog);
