MODx.page.CreateDashboardWidget = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        formpanel: 'modx-panel-dashboard-widget'
        ,actions: {
            'new': 'system/dashboard/widget/create'
            ,edit: 'system/dashboard/widget/update'
            ,cancel: 'system/dashboards'
        }
        ,buttons: [{
            process: 'system/dashboard/widget/create'
            ,reload: true
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
            ,record: config.record || {}
        }]
	});
	MODx.page.CreateDashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateDashboardWidget,MODx.Component);
Ext.reg('modx-page-dashboard-widget-create',MODx.page.CreateDashboardWidget);
