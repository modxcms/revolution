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
            ,text: _('create')
            ,id: 'modx-abtn-new'
            ,cls: 'primary-button'
            ,params: {
                a:'Security/Role/Create'
            }
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'grid-role'
            ,renderTo: 'role_grid'
        }]
    });
    MODx.page.ListRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ListRoles,MODx.Component);
Ext.reg('page-roles',MODx.page.ListRoles);
