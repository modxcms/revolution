/**
 * Loads the chunk update page
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
            'new': MODx.action['security/forms/profile/create']
            ,edit: MODx.action['security/forms/profile/update']
            ,cancel: MODx.action['security/forms']
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
            ,params: {a:MODx.action['security/forms']}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-fc-profile'
            ,renderTo: 'modx-panel-fc-profile-div'
            ,record: config.record || {}
            ,baseParams: { action: 'update' ,id: config.id }
        }]
	});
	MODx.page.UpdateFCProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateFCProfile,MODx.Component);
Ext.reg('modx-page-fc-profile-update',MODx.page.UpdateFCProfile);