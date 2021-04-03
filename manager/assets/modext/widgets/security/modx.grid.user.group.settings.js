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
            action: 'Security/Group/Setting/GetList'
            ,group: config.group
        }
        ,saveParams: {
            group: config.group
        }
        ,save_action: 'Security/Group/Setting/UpdateFromGrid'
        ,fk: config.group
        ,tbar: [{
            text: _('create')
            ,cls:'primary-button'
            ,scope: this
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: MODx.config.connector_url
                ,baseParams: {
                    action: 'Security/Group/Setting/Create'
                }
                ,fk: config.group
            }
        }]
    });
    MODx.grid.GroupSettings.superclass.constructor.call(this,config);
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

        var m = [];
        if (this.menu.record.menu) {
            m = this.menu.record.menu;
        } else {
            m.push({
                text: _('edit')
                ,handler: this.updateSetting
            },'-',{
                text: _('remove')
                ,handler: this.remove.createDelegate(this,['setting_remove_confirm', 'Security/Group/Setting/Remove'])
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
            ,action: 'Security/Group/Setting/Update'
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
Ext.reg('modx-grid-group-settings',MODx.grid.GroupSettings);
