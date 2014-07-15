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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/user/setting/getList'
            ,user: config.user
        }
        ,saveParams: {
            user: config.user
        }
        ,save_action: 'security/user/setting/updatefromgrid'
        ,fk: config.user
        ,tbar: [{
            text: _('create_new')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: { 
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'security/user/setting/create'
                }
                ,fk: config.user
            }
        }]
    });
    MODx.grid.UserSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.UserSettings,MODx.grid.SettingsGrid);
Ext.reg('modx-grid-user-settings',MODx.grid.UserSettings);
