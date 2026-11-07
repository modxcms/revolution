/**
 * Loads a grid of Group Settings
 *
 * @class MODx.grid.GroupSettings
 * @extends MODx.grid.SettingsGrid
 * @param {Object} config An object of options.
 * @xtype modx-grid-group-settings
 */
MODx.grid.GroupSettings = function(config = {}) {
    this.settingsType = 'usergroup';
    this.querySpec = [3, 'query', 'tab', true];
    Ext.applyIf(config,{
        title: _('user_group_settings')
        ,id: 'modx-grid-group-settings'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Group/Setting/GetList',
            group: config.group,
            namespace: this.applyRequestFilter(3, 'ns', 'tab'),
            area: MODx.util.url.getParamValue('area') || null
        }
        ,saveParams: {
            group: config.group
        }
        ,save_action: 'Security/Group/Setting/UpdateFromGrid'
        ,fk: config.group
        ,tbar: [this.getCreateButton('setting', 'createSetting')]
    });
    MODx.grid.GroupSettings.superclass.constructor.call(this,config);

    // Settings only have one universal permissions specification: 'settings'
    this.setUserCanCreate(['settings', 'usergroup_edit', 'usergroup_save']);
    this.userCanEdit = this.userCanCreate;
    this.userCanDelete = this.userCanCreate;
};
Ext.extend(MODx.grid.GroupSettings,MODx.grid.SettingsGrid, {
    _showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();

        let menu = [];

        if (this.menu.record.menu) {
            menu = this.menu.record.menu;
        } else {
            if (this.userCanEdit) {
                menu.push({
                    text: _('edit'),
                    handler: this.updateSetting
                });
            }
            if (this.userCanDelete) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('remove'),
                    handler: this.remove.createDelegate(this, ['setting_remove_confirm', 'Security/Group/Setting/Remove'])
                });
            }
        }
        if (menu.length > 0) {
            this.addContextMenuItem(menu);
            this.menu.showAt(e.xy);
        }
    }

    ,createSetting: function(btn, e) {
        this.windows.createSetting = MODx.load({
            xtype: 'modx-window-setting-create',
            action: 'Security/Group/Setting/Create',
            fk: this.config?.fk || 0,
            url: this.config?.url,
            grid: this,
            listeners: {
                success: {
                    fn: function(response) {
                        this.refresh();
                        this.fireEvent('createSetting', response);
                    },
                    scope: this
                }
            }
        });
        this.windows.createSetting.show(e.target);
    }

    ,updateSetting: function(btn, e) {
        const { record } = this.menu;
        record.fk = this.config?.fk || 0;
        this.windows.updateSetting = MODx.load({
            xtype: 'modx-window-setting-update',
            action: 'Security/Group/Setting/Update',
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
Ext.reg('modx-grid-group-settings',MODx.grid.GroupSettings);
