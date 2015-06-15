/**
 * Loads the update user page
 * 
 * @class MODx.page.UpdateUser
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-user-update
 */
MODx.page.UpdateUser = function(config) {
	config = config || {};
	Ext.applyIf(config,{
       formpanel: 'modx-panel-user'
       ,actions: {
            'new': 'security/user/create'
            ,edit: 'security/user/update'
            ,cancel: 'security/user'
       }
        ,buttons: [{
            process: 'security/user/update'
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
                MODx.loadPage('security/user')
            }
        },{
            text: _('delete')
            ,id: 'modx-abtn-delete'
            ,handler: this.removeUser
            ,scope: this
        },{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-user'
            ,renderTo: 'modx-panel-user-div'
            ,user: config.user
            ,remoteFields: config.remoteFields
            ,extendedFields: config.extendedFields
            ,name: ''
        }]
	});
	MODx.page.UpdateUser.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateUser,MODx.Component,{
    removeUser: function(btn,e) {
        MODx.msg.confirm({
            title: _('user_remove')
            ,text: _('user_confirm_remove')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'security/user/delete'
                ,id: this.config.user
            }
            ,listeners: {
                'success': {fn:function(r) {
                    MODx.loadPage('security/user');
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-page-user-update',MODx.page.UpdateUser);
