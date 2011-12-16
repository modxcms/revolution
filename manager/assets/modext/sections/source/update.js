MODx.page.UpdateSource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-source'
       ,actions: {
            'new': MODx.action['source/create']
            ,edit: MODx.action['source/update']
            ,cancel: MODx.action['source']
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
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['source/index']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
		,components: [{
            xtype: 'modx-panel-source'
            ,record: config.record
            ,defaultProperties: config.defaultProperties || []
        }]
	});
	MODx.page.UpdateSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateSource,MODx.Component);
Ext.reg('modx-page-source-update',MODx.page.UpdateSource);
