/**
 * Loads the usergroup create page
 * 
 * @class MODx.page.CreateUserGroup
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-user-group-create
 */
MODx.page.CreateUserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-user-group'
        ,actions: {
            'new': MODx.action['security/usergroup/create']
            ,edit: MODx.action['security/usergroup/update']
            ,cancel: MODx.action['security/permission']
        }
        ,buttons: [{
            process: 'create'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['security/permission']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-user-group'
            ,record: config.record || {}
            ,renderTo: 'modx-panel-user-group-div'
        }]
    });
    MODx.page.CreateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateUserGroup,MODx.Component);
Ext.reg('modx-page-user-group-create',MODx.page.CreateUserGroup);