/**
 * Loads the access policy update page
 *
 * @class MODx.page.UpdateAccessPolicy
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-access-policy
 */
MODx.page.UpdateAccessPolicy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-access-policy'
        ,actions: {
            'new': 'security/access/policy'
            ,edit: 'Security/Access/Policy/Update'
            ,cancel: 'security/permission'
        }
        ,buttons: [{
            process: 'Security/Access/Policy/Update'
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
            ,params: {a:'security/permission'}
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-access-policy'
            ,policy: config.policy
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicy,MODx.Component);
Ext.reg('modx-page-access-policy',MODx.page.UpdateAccessPolicy);
