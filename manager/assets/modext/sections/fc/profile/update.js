/**
 * Loads the FC Profile update page
 *
 * @class MODx.page.UpdateFCProfile
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-fc-profile-update
 */
MODx.page.UpdateFCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-fc-profile'
        ,actions: {
            'new': 'Security/Forms/Profile/Create'
            ,edit: 'Security/Forms/Profile/Update'
            ,cancel: 'security/forms'
        }
        ,buttons: [{
            process: 'Security/Forms/Profile/Update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls:'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            process: 'cancel'
            ,text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,params: {a:'security/forms'}
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-fc-profile'
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateFCProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateFCProfile,MODx.Component);
Ext.reg('modx-page-fc-profile-update',MODx.page.UpdateFCProfile);
