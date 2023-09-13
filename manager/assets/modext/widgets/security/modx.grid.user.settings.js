/**
 * Loads a grid of User Settings
 * 
 * @class MODx.grid.UserSettings
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-settings
 */
MODx.grid.UserSettings = function(config = {}) {
    this.settingsType = 'user';
    Ext.applyIf(config,{
        title: _('user_settings')
        ,id: 'modx-grid-user-settings'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/User/Setting/GetList',
            user: config.user,
            namespace: MODx.util.url.getParamValue('ns'),
            area: MODx.util.url.getParamValue('area')
        }
        ,saveParams: {
            user: config.user
        }
        ,save_action: 'Security/User/Setting/UpdateFromGrid'
        ,fk: config.user
        ,tbar: [{
            text: _('create')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'Security/User/Setting/Create'
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
                ,fk: config.user
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
    MODx.grid.UserSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.UserSettings,MODx.grid.SettingsGrid, {
    _showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();

        var m = [];
        if (this.menu.record.menu) {
            m = this.menu.record.menu;
        } else {
            m.push({
                text: _('edit')
                ,handler: this.updateSetting
            },'-',{
                text: _('remove')
                ,handler: this.remove.createDelegate(this,['setting_remove_confirm', 'Security/User/Setting/Remove'])
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    }

    ,updateSetting: function(btn, e) {
        const { record } = this.menu;
        record.fk = this.config?.fk || 0;
        this.windows.updateSetting = MODx.load({
            xtype: 'modx-window-setting-update',
            action: 'Security/User/Setting/Update',
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
Ext.reg('modx-grid-user-settings',MODx.grid.UserSettings);
