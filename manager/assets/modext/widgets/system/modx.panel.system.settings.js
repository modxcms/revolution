/**
 * Loads the panel for managing system settings.
 * 
 * @class MODx.panel.SystemSettings
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-system-settings
 */
MODx.panel.SystemSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-system-settings'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('system_settings')+'</h2>'
            ,border: false
            ,id: 'modx-system-settings-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,autoHeight: true
            ,defaults: { border: false }
            ,items: [{
                html: '<p>'+_('settings_desc')+'</p>'
               ,bodyCssClass: 'panel-desc'
            },{
                xtype: 'modx-grid-system-settings'
				,cls: 'main-wrapper'
				,preventSaveRefresh: true
            },{
                html: MODx.onSiteSettingsRender
            }]
        }]
    });
    MODx.panel.SystemSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.SystemSettings,MODx.FormPanel);
Ext.reg('modx-panel-system-settings',MODx.panel.SystemSettings);


/**
 * Loads a grid of System Settings
 * 
 * @class MODx.grid.SystemSettings
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-system-settings
 */
MODx.grid.SystemSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'system/settings.php'
    });
    MODx.grid.SystemSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SystemSettings,MODx.grid.SettingsGrid);
Ext.reg('modx-grid-system-settings',MODx.grid.SystemSettings);
