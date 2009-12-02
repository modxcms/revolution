Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-access-permissions' });
});

/**
 * Loads the access permissions page
 * 
 * @class MODx.page.AccessPermissions
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-access-permissions
 */
MODx.page.AccessPermissions = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{ 
            xtype: 'modx-grid-access-context'
            ,el: 'access_context_grid' 
        },{
            xtype: 'modx-grid-access-resource-group'
            ,el: 'access_resourcegroup_grid'
        }]
        ,deferredRender: true
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.AccessPermissions.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.AccessPermissions,MODx.Component);
Ext.reg('modx-page-access-permissions',MODx.page.AccessPermissions);