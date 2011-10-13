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
        },MODx.getPageStructure([{
            title: _('user_groups')
            ,autoHeight: true
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('user_group_management_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                title: ''
                ,xtype: 'modx-tree-usergroup'
				,cls:'main-wrapper'
            }]
        },{
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
        },{
            title: _('policies')
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
        },{
            title: _('policy_templates') 
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
