MODx.page.UpdateDashboard = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-dashboard'
       ,actions: {
            'new': 'system/dashboards/create'
            ,edit: 'system/dashboards/update'
            ,cancel: 'system/dashboards'
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
            process: 'cancel', text: _('cancel'), params: {a:'system/dashboards'}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
		,components: [{
            xtype: 'modx-panel-dashboard'
            ,renderTo: 'modx-panel-dashboard-div'
            ,record: config.record
        }]
	});
	MODx.page.UpdateDashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateDashboard,MODx.Component);
Ext.reg('modx-page-dashboard-update',MODx.page.UpdateDashboard);