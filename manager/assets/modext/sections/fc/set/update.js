/**
 * Loads the FC set update page
 *
 * @class MODx.page.UpdateFCSet
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-fc-profile-update
 */
MODx.page.UpdateFCSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-fc-set'
        ,actions: {
            'new': 'Security/Forms/Set/Create'
            ,edit: 'Security/Forms/Set/Update'
            ,cancel: 'security/forms'
        }
        ,buttons: [{
            process: 'Security/Forms/Set/Update'
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
            ,params: {a:'Security/Forms/Profile/Update', id: config.record.profile}
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-fc-set'
            ,record: config.record || {}
        }]
    });
    MODx.page.UpdateFCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateFCSet,MODx.Component);
Ext.reg('modx-page-fc-set-update',MODx.page.UpdateFCSet);
