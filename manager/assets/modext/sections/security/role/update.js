Ext.onReady(function() {
    MODx.load({
    	xtype: 'page-role-update'
        ,id: MODx.request.id
    });
});

/**
 * 
 * @class MODx.page.UpdateRole
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-update-role
 */
MODx.page.UpdateRole = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        form: 'mutate_role'
        ,actions: {
            'new': MODx.action['security/role/create']
            ,edit: MODx.action['security/role/update']
            ,cancel: MODx.action['security/role']
        }
        ,buttons: [{
            process: 'update', text: _('save'), method: 'remote'
        },{
            process: 'duplicate', text: _('duplicate'), method: 'remote', confirm: _('confirm_duplicate_role')
        },{
            process: 'delete', text: _('delete'), method: 'remote', confirm: _('confirm_delete_role')
        },{
            process: 'cancel', text: _('cancel'), params:{a:MODx.action['security/role']}
        }]
        ,loadStay: true
        ,tabs: [
            {contentEl: 'tab_information', title: _('settings_general')}
            ,{contentEl: 'tab_permissions', title: _('permissions')}
            ,{
                xtype: 'grid-roleuser'
                ,el: 'role_users_grid'
                ,role: config.id
                ,preventRender: true
            }
        ]
    });
	MODx.page.UpdateRole.superclass.constructor.call(this,config);	
};
Ext.extend(MODx.page.UpdateRole,MODx.Component);
Ext.reg('page-role-update',MODx.page.UpdateRole);