
/**
 * Loads the usergroup update page
 * 
 * @class MODx.page.UpdateUserGroup
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-user-group-update
 */
MODx.page.UpdateUserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-user-group'
        ,buttons: [{
            process: 'update'
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
            ,usergroup: MODx.request.id
        }]
    });
    MODx.page.UpdateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateUserGroup,MODx.Component);
Ext.reg('modx-page-user-group-update',MODx.page.UpdateUserGroup);