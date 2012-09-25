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
            ,edit: 'security/access/policy/update'
            ,cancel: 'security/permission'
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
            ,params: {a:'security/permission'}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{ 
            xtype: 'modx-panel-access-policy'
            ,renderTo: 'modx-panel-access-policy-div'
            ,policy: config.policy
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateAccessPolicy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateAccessPolicy,MODx.Component);
Ext.reg('modx-page-access-policy',MODx.page.UpdateAccessPolicy);