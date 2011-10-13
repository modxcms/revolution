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
        ,url: MODx.config.connectors_url+'context/setting.php'
        ,baseParams: {
            action: 'getList'
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
            ,handler: { 
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connectors_url+'context/setting.php'
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
 * @extends MODx.Window
 * @param {Object} config An object of config properties
 * @xtype modx-window-context-setting-update
 */
MODx.window.UpdateContextSetting = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('setting_update')
        ,height: 200
        ,width: 600
        ,url: MODx.config.connectors_url+'context/setting.php'
        ,action: 'update'
        ,bodyStyle: 'padding: 0'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'context_key'
            ,id: 'modx-ucs-context_key'
            ,value: r.context_key
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('key')
            ,name: 'key'
            ,id: 'modx-ucs-key'
            ,allowBlank: false
            ,value: r.key
            ,submitValue: r.key
            ,anchor: '100%'
        },{
            xtype: r.xtype || 'textfield'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-ucs-value'
            ,allowBlank: false
            ,value: r.value
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: 'xtype'
            ,id: 'modx-ucs-xtype'
            ,value: r.xtype
        },{
            xtype: 'hidden'
            ,name: 'area'
            ,id: 'modx-ucs-area'
            ,value: r.area
        },{
            xtype: 'hidden'
            ,name: 'namespace'
            ,id: 'modx-ucs-namespace'
            ,value: r.namespace
        }]
    });
    MODx.window.UpdateContextSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateContextSetting,MODx.Window);
Ext.reg('modx-window-context-setting-update',MODx.window.UpdateContextSetting);
