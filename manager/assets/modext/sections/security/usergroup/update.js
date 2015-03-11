
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
            process: 'security/group/update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            // ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('security/permission')
            }
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-user-group'
            ,record: config.record || {}
            ,usergroup: MODx.request.id
        }]
    });
    MODx.page.UpdateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateUserGroup,MODx.Component);
Ext.reg('modx-page-user-group-update',MODx.page.UpdateUserGroup);
