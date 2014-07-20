MODx.page.UpdateSource = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-source'
       ,actions: {
            'new': 'source/create'
            ,edit: 'source/update'
            ,cancel: 'source'
       }
       ,buttons: [{
            process: 'source/update'
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
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {a:'source'}
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
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
