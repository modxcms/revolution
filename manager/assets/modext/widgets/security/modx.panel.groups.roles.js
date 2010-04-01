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
             html: '<h2>'+_('user_group_management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-access-permissions-header'
        },MODx.getPageStructure([{
            title: _('user_groups')
            ,bodyStyle: 'padding: 15px;'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('user_group_management_msg')+'</p>'
                ,border: false
            },{
                title: ''
                ,xtype: 'modx-tree-usergroup'
            }]
        },{
            title: _('roles')
            ,bodyStyle: 'padding: 15px;'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('roles_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-role'
                ,title: ''
                ,preventRender: true
            }]
        },{
            title: _('policies')
            ,bodyStyle: 'padding: 15px'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('policy_management_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-access-policy'
                ,preventRender: true
            }]
        }],{
            stateful: true
            ,stateId: 'access-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
        })]
    });
    MODx.panel.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GroupsRoles,MODx.FormPanel);
Ext.reg('modx-panel-groups-roles',MODx.panel.GroupsRoles);