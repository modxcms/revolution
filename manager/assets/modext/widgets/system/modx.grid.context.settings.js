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
        ,save_action: 'context/setting/updatefromgrid'
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
                ,keyField: {
                    xtype: 'modx-combo-setting-key'
                    ,fieldLabel: _('key')
                    ,name: 'key'
                    ,id: 'modx-cs-key'
                    ,maxLength: 100
                    ,anchor: '100%'
                    ,listeners: {
                        'render': {
                            fn: function(key) {
                                key.getStore().baseParams.namespace = Ext.getCmp('modx-cs-namespace').getValue();

                                Ext.getCmp('modx-cs-namespace').on('select', function(combo, item) {
                                    key.getStore().baseParams.namespace = item.data.name;
                                    key.getStore().load();
                                }, this);
                            }
                            ,scope: this
                        }
                    }
                }
                ,fk: config.context_key
            }
        }]
    });
    MODx.grid.ContextSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ContextSettings,MODx.grid.SettingsGrid, {
    removeSetting: function() {
        return this.remove('setting_remove_confirm', 'context/setting/remove');
    }
    ,updateSetting: function(btn,e) {
        var r = this.menu.record;
        r.fk = Ext.isDefined(this.config.fk) ? this.config.fk : 0;
        var uss = MODx.load({
            xtype: 'modx-window-setting-update'
            ,action: 'context/setting/update'
            ,record: r
            ,grid: this
            ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                },scope:this}
            }
        });
        uss.reset();
        uss.setValues(r);
        uss.show(e.target);
    }
});
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
