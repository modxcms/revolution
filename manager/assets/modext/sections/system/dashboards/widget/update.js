MODx.page.UpdateDashboardWidget = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        formpanel: 'modx-panel-dashboard-widget'
        ,actions: {
            'new': 'system/dashboard/widget/create'
            ,edit: 'system/dashboard/widget/update'
            ,cancel: 'system/dashboards'
        }
        ,buttons: [{
            process: 'system/dashboard/widget/update'
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
            xtype: 'modx-panel-dashboard-widget'
            ,record: config.record
        }]
	});
	MODx.page.UpdateDashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateDashboardWidget,MODx.Component);
Ext.reg('modx-page-dashboard-widget-update',MODx.page.UpdateDashboardWidget);
