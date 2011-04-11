Ext.onReady(function() {
	MODx.load({
	   xtype: 'page-role-create'
	});	
});

/**
 * @class MODx.page.CreateRole
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-role-create
 */
MODx.page.CreateRole = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		form: 'mutate_role'
		,actions: {
            'new': MODx.action['security/role/create']
            ,edit: MODx.action['security/role/update']
            ,cancel: MODx.action['security/role']
        }
        ,buttons: [{
            process: 'create', text: _('save'), method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params:{a:MODx.action['security/role']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_information', title: _('settings_general')}
            ,{contentEl: 'tab_permissions', title: _('permissions')}
        ]
	});
	MODx.page.CreateRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateRole,MODx.Component);
Ext.reg('page-role-create',MODx.page.CreateRole);