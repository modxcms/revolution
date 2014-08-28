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
		,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,forceLayout: true
        ,items: [{ 
             html: '<h2>'+_('user_group_management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-access-permissions-header'
        },MODx.getPageStructure(this.getPageTabs(config),{
            id: 'modx-access-permissions-tabs'
            ,stateful: true
            ,stateId: 'access-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
        })]
    });
    MODx.panel.GroupsRoles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.GroupsRoles,MODx.FormPanel,{
    getPageTabs: function(config) {
        var tbs = [];
        if (MODx.perm.usergroup_view == 1) {
            tbs.push({
                title: _('user_groups') + ' & ' + _('users')
                ,autoHeight: true
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('user_group_management_msg')+'</p>'
                    ,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-tabs'
                    ,id: 'modx-grid-user-group-tabs'
                    ,items: [{
                        title: _('user_groups')
                        ,items: [{
                            html: '<p>'+_('user_group_grid_management_msg')+'</p>'
                            ,bodyCssClass: 'panel-desc'
                            ,border: false
                        },{
                            xtype: 'modx-grid-user-group-groups'
                            ,cls:'main-wrapper'
                            ,listeners: {
                                rowclick: {
                                    fn: function(grid,rowIndex,e){
                                        var record = grid.getStore().getAt(rowIndex);
                                        this.openTab(record);
                                    },scope: this
                                }
                            }
                        }]
                    }]
                }]
            });
        }
        if (MODx.perm.view_role == 1) {
            tbs.push({
                title: _('roles')
                ,autoHeight: true
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('roles_msg')+'</p>'
                    ,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-role'
                    ,cls:'main-wrapper'
                    ,title: ''
                    ,preventRender: true
                }]
            });
        }
        if (MODx.perm.policy_view == 1) {
            tbs.push({
                title: _('policies')
                ,id: 'modx-panel-access-policies'
                ,autoHeight: true
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('policy_management_msg')+'</p>'
                    ,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-access-policy'
                    ,cls:'main-wrapper'
                }]
            });
        }
        if (MODx.perm.policy_template_view) {
            tbs.push({
                title: _('policy_templates')
                ,id: 'modx-panel-access-policy-templates'
                ,autoHeight: true
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('policy_templates.intro_msg')+'</p>'
                    ,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-access-policy-templates'
                    ,cls:'main-wrapper'
                }]
            });
        }
        return tbs;
    }
    ,openTab: function(record) {
        var groupTab = Ext.getCmp('modx-grid-user-group-tabs');
        if (typeof(groupTab) === 'undefined') {
            return false;
        }
        var usergroup = record.data.id ? record.data.id : 'anonymous';
        var check = Ext.getCmp('modx-grid-usergroup-users-' + usergroup);
        if (typeof(check) !== 'undefined') {
            groupTab.activate(check);
        } else {
            groupTab.add({
                title: record.data.name
                ,id: 'modx-grid-usergroup-users-' + usergroup
                ,cls: 'main-wrapper'
                ,closable: true
                ,items: [{
                    xtype: 'modx-grid-usergroup-users'
                    ,usergroup: usergroup
                }]
            });
            groupTab.activate(Ext.getCmp('modx-grid-usergroup-users-' + usergroup));
        }
    }
});
Ext.reg('modx-panel-groups-roles',MODx.panel.GroupsRoles);