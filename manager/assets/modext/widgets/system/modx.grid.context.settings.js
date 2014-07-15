/**
 * Loads a grid of Context Settings
 *
 * @class MODx.grid.ContextSettings
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-context-settings
 */
MODx.grid.ContextSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('context_settings')
        ,id: 'modx-grid-context-settings'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'context/setting/getList'
            ,context_key: config.context_key
        }
        ,saveParams: {
            context_key: config.context_key
        }
        ,fk: config.context_key
        ,autosave: false
        ,tbar: [{
            text: _('create_new')
            ,scope: this
            ,cls:'primary-button'
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'context/setting/create'
                }
                ,fk: config.context_key
            }
        }]
    });
    MODx.grid.ContextSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ContextSettings,MODx.grid.SettingsGrid);
Ext.reg('modx-grid-context-settings',MODx.grid.ContextSettings);



/**
 * Update a Context Setting
 *
 * @class MODx.window.UpdateContextSetting
 * @extends MODx.window.UpdateSetting
 * @param {Object} config An object of config properties
 * @xtype modx-window-context-setting-update
 */
MODx.window.UpdateContextSetting = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('setting_update')
        ,action: 'context/setting/update'
        ,fk: r.context_key
    });
    MODx.window.UpdateContextSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateContextSetting,MODx.window.UpdateSetting);
Ext.reg('modx-window-context-setting-update',MODx.window.UpdateContextSetting);
