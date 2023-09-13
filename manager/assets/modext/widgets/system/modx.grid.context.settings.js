/**
 * Loads a grid of Context Settings
 *
 * @class MODx.grid.ContextSettings
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-context-settings
 */
MODx.grid.ContextSettings = function(config = {}) {
    this.settingsType = 'context';
    Ext.applyIf(config,{
        title: _('context_settings')
        ,id: 'modx-grid-context-settings'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Context/Setting/GetList',
            context_key: config.context_key,
            namespace: MODx.util.url.getParamValue('ns'),
            area: MODx.util.url.getParamValue('area')
        }
        ,saveParams: {
            context_key: config.context_key
        }
        ,fk: config.context_key
        ,save_action: 'Context/Setting/UpdateFromGrid'
        ,tbar: [{
            text: _('create')
            ,scope: this
            ,cls:'primary-button'
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'Context/Setting/Create'
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
                ,listeners: {
                    success: {
                        fn: function(response) {
                            this.refresh();
                            this.fireEvent('createSetting', response);
                        },
                        scope: this
                    }
                }
            }
        }]
    });
    MODx.grid.ContextSettings.superclass.constructor.call(this,config);
};

Ext.extend(MODx.grid.ContextSettings,MODx.grid.SettingsGrid, {
    removeSetting: function() {
        return this.remove('setting_remove_confirm', 'Context/Setting/Remove');
    }

    ,updateSetting: function(btn, e) {
        const { record } = this.menu;
        record.fk = this.config?.fk || 0;
        this.windows.updateSetting = MODx.load({
            xtype: 'modx-window-setting-update',
            action: 'Context/Setting/Update',
            record: record,
            grid: this,
            listeners: {
                success: {
                    fn: function(response) {
                        this.refresh();
                        this.fireEvent('updateSetting', response);
                    },
                    scope: this
                }
            }
        });
        this.windows.updateSetting.setValues(record);
        this.windows.updateSetting.show(e.target);
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
        title: _('edit')
        ,action: 'Context/Setting/Update'
        ,fk: r.context_key
    });
    MODx.window.UpdateContextSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateContextSetting,MODx.window.UpdateSetting);
Ext.reg('modx-window-context-setting-update',MODx.window.UpdateContextSetting);
