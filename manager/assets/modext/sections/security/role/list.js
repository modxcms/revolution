Ext.onReady(function() {
	MODx.load({ xtype: 'page-roles' });
});

/**
 * Loads the Role management page
 *
 * @class MODx.page.ListRoles
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-roles
 */
MODx.page.ListRoles = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		buttons: [{
            process: 'new'
            ,text: _('new')
            ,params: {
                a:'security/role/create'
            }
        },'-',{
            text: _('cancel')
        }/*,'-',{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }*/]
        ,components: [{
            xtype: 'grid-role'
            ,renderTo: 'role_grid'
        }]
	});
	MODx.page.ListRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ListRoles,MODx.Component);
Ext.reg('page-roles',MODx.page.ListRoles);
