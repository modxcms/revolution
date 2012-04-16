MODx.page.CreateDashboardWidget = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-dashboard-widget'
       ,actions: {
            'new': 'system/dashboards/widget/create'
            ,edit: 'system/dashboards/widget/update'
            ,cancel: 'system/dashboards'
       }
       ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,checkDirty: false
            ,id: 'modx-btn-save'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:'system/dashboards'}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
		,components: [{
            xtype: 'modx-panel-dashboard-widget'
            ,renderTo: 'modx-panel-dashboard-widget-div'
            ,record: config.record || {}
        }]
	});
	MODx.page.CreateDashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateDashboardWidget,MODx.Component);
Ext.reg('modx-page-dashboard-widget-create',MODx.page.CreateDashboardWidget);