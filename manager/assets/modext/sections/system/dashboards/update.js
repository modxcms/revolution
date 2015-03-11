MODx.page.UpdateDashboard = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        formpanel: 'modx-panel-dashboard'
        ,actions: {
            'new': 'system/dashboard/create'
            ,edit: 'system/dashboard/update'
            ,cancel: 'system/dashboards'
        }
        ,buttons: [{
            process: 'system/dashboard/update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            // ,checkDirty: false
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('system/dashboards');
            }
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
		,components: [{
            xtype: 'modx-panel-dashboard'
            ,record: config.record
        }]
	});
	MODx.page.UpdateDashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateDashboard,MODx.Component);
Ext.reg('modx-page-dashboard-update',MODx.page.UpdateDashboard);
