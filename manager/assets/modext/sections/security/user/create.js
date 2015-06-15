/**
 * Loads the create user page 
 * 
 * @class MODx.page.CreateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-user-create
 */
MODx.page.CreateUser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-user'
        ,buttons: [{
            process: 'security/user/create'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,redirect: false
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
                MODx.loadPage('security/user')
            }
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-user'
            ,renderTo: 'modx-panel-user-div'
            ,user: 0
            ,name: ''
        }]
	});
	MODx.page.CreateUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateUser,MODx.Component);
Ext.reg('modx-page-user-create',MODx.page.CreateUser);
