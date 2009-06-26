/**
 * @class MODx.panel.GroupsRoles
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-groups-roles
 */
MODx.panel.GroupsRoles = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-groups-roles'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{ 
             html: '<h2>'+_('access_permissions')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-access-permissions-header'
        },MODx.getPageStructure([{
            title: _('user_groups')
            ,bodyStyle: 'padding: 1.5em;'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('user_group_management_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-tree-usergroup'
                ,title: ''
            }]
        },{
            title: _('roles')
            ,bodyStyle: 'padding: 1.5em;'
            ,autoHeight: true
            ,items: [{
                xtype: 'modx-grid-role'
                ,title: ''
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GroupsRoles,MODx.FormPanel);
Ext.reg('modx-panel-groups-roles',MODx.panel.GroupsRoles);