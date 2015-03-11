/**
 * Loads the access policy template update page
 *
 * @class MODx.page.UpdateAccessPolicyTemplate
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-access-policy-template
 */
MODx.page.UpdateAccessPolicyTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-access-policy-template'
        ,actions: {
            'new': 'security/access/policy/template'
            ,edit: 'security/access/policy/template/update'
            ,cancel: 'security/permission'
        }
        ,buttons: [{
            process: 'security/access/policy/template/update'
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
            ,params: {a:'security/permission'}
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{ 
            xtype: 'modx-panel-access-policy-template'
            ,template: config.template
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateAccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicyTemplate,MODx.Component);
Ext.reg('modx-page-access-policy-template',MODx.page.UpdateAccessPolicyTemplate);
