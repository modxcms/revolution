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
            'new': MODx.action['security/forms/set/create']
            ,edit: MODx.action['security/forms/set/update']
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
            ,params: {a:MODx.action['security/forms/profile/update'], id: config.record.profile}
        },'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-fc-set'
            ,renderTo: 'modx-panel-fc-set-div'
            ,record: config.record || {}
            ,baseParams: { action: 'update' ,id: config.id }
        }]
	});
	MODx.page.UpdateFCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateFCSet,MODx.Component);
Ext.reg('modx-page-fc-set-update',MODx.page.UpdateFCSet);