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
            'new': 'Security/Role/Create'
            ,edit: 'Security/Role/Update'
            ,cancel: 'security/role'
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {a:'security/role'}
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,tabs: [
            {contentEl: 'tab_information', title: _('settings_general')}
            ,{contentEl: 'tab_permissions', title: _('permissions')}
        ]
    });
    MODx.page.CreateRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateRole,MODx.Component);
Ext.reg('page-role-create',MODx.page.CreateRole);
