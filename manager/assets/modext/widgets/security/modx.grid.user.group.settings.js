/**
 * Loads a grid of Group Settings
 *
 * @class MODx.grid.GroupSettings
 * @extends MODx.grid.SettingsGrid
 * @param {Object} config An object of options.
 * @xtype modx-grid-group-settings
 */
MODx.grid.GroupSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_group_settings')
        ,id: 'modx-grid-group-settings'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/setting/getList'
            ,group: config.group
        }
        ,saveParams: {
            group: config.group
        }
        ,save_action: 'security/group/setting/updatefromgrid'
        ,fk: config.group
        ,tbar: [{
            text: _('create_new')
            ,cls:'primary-button'
            ,scope: this
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'security/group/setting/create'
                }
                ,fk: config.group
            }
        }]
    });
    MODx.grid.GroupSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.GroupSettings,MODx.grid.SettingsGrid);
Ext.reg('modx-grid-group-settings',MODx.grid.GroupSettings);
