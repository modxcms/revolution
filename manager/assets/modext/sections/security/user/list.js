/**
 * Loads the users page
 * 
 * @class MODx.page.Users
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-users
 */
MODx.page.Users = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'modx-panel-users'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Users,MODx.Component);
Ext.reg('modx-page-users',MODx.page.Users);
