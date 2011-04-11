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
            'new': MODx.action['security/access/policy/template']
            ,edit: MODx.action['security/access/policy/template/update']
            ,cancel: MODx.action['security/permission']
        }
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,checkDirty: false
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
            xtype: 'modx-panel-access-policy-template'
            ,renderTo: 'modx-panel-access-policy-template-div'
            ,template: config.template
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateAccessPolicyTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicyTemplate,MODx.Component);
Ext.reg('modx-page-access-policy-template',MODx.page.UpdateAccessPolicyTemplate);