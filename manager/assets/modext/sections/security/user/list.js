Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-users' });
});

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
            ,renderTo: 'modx-panel-users'
        }]
	});
	MODx.page.Users.superclass.constructor.call(this,config);
    setTimeout("Ext.getCmp('modx-layout').removeAccordion();",1000);
};
Ext.extend(MODx.page.Users,MODx.Component);
Ext.reg('modx-page-users',MODx.page.Users);