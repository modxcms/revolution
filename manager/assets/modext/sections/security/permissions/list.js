Ext.onReady(function() {
	MODx.add('modx-page-groups-roles');
});

/**
 * Loads the groups and roles page
 * 
 * @class MODx.page.GroupsRoles
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-groups-roles
 */
MODx.page.GroupsRoles = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-groups-roles'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.GroupsRoles,MODx.Component);
Ext.reg('modx-page-groups-roles',MODx.page.GroupsRoles);