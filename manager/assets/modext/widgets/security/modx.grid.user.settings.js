/**
 * Loads a grid of User Settings
 * 
 * @class MODx.grid.UserSettings
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-settings
 */
MODx.grid.UserSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_settings')
        ,id: 'modx-grid-user-settings'
        ,url: MODx.config.connectors_url+'security/user/setting.php'
        ,baseParams: {
            action: 'getList'
            ,user: config.user
        }
        ,saveParams: {
            user: config.user
        }
        ,fk: config.user
        ,tbar: [{
            text: _('create_new')
            ,scope: this
            ,handler: { 
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connectors_url+'security/user/setting.php'
                ,fk: config.user
            }
        }]
    });
    MODx.grid.UserSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.UserSettings,MODx.grid.SettingsGrid);
Ext.reg('modx-grid-user-settings',MODx.grid.UserSettings);