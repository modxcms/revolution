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
            action: 'Security/User/Setting/GetList'
            ,user: config.user
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

    ,updateSetting: function(btn,e) {
        var r = this.menu.record;
        r.fk = Ext.isDefined(this.config.fk) ? this.config.fk : 0;
        var uss = MODx.load({
            xtype: 'modx-window-setting-update'
            ,action: 'Security/User/Setting/Update'
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
Ext.reg('modx-grid-user-settings',MODx.grid.UserSettings);
