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
            'new': MODx.action['security/user/create']
            ,edit: MODx.action['security/user/update']
            ,cancel: MODx.action['security/user']
       }
        ,buttons: [{
            process: 'update', text: _('save'), method: 'remote'
            ,checkDirty: true
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['security/user']}
        },'-',{
            text: _('delete')
            ,handler: this.removeUser
            ,scope: this
        },'-',{
            text: _('help_ex')
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
            ,url: MODx.config.connectors_url+'security/user.php'
            ,params: {
                action: 'delete'
                ,id: this.config.user
            }
            ,listeners: {
            	'success': {fn:function(r) {
            	    location.href = '?a='+MODx.action['security/user'];
            	},scope:this}
            }
        });
    }
});
Ext.reg('modx-page-user-update',MODx.page.UpdateUser);