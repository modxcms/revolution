MODx.page.UpdateDashboardWidget = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-dashboard-widget'
       ,actions: {
            'new': MODx.action['system/dashboards/widget/create']
            ,edit: MODx.action['system/dashboards/widget/update']
            ,cancel: MODx.action['system/dashboards']
       }
       ,buttons: [{
            process: 'update', text: _('save'), method: 'remote'
            ,checkDirty: false
            ,id: 'modx-btn-save'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['system/dashboards']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
		,components: [{
            xtype: 'modx-panel-dashboard-widget'
            ,renderTo: 'modx-panel-dashboard-widget-div'
            ,record: config.record
        }]
	});
	MODx.page.UpdateDashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateDashboardWidget,MODx.Component);
Ext.reg('modx-page-dashboard-widget-update',MODx.page.UpdateDashboardWidget);