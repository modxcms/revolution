/**
 * @class MODx.panel.GroupsRoles
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-groups-roles
 */
MODx.panel.GroupsRoles = function(config = {}) {
    this.currentGroupId = 0;
    Ext.applyIf(config, {
        id: 'modx-panel-groups-roles',
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        forceLayout: true,
        items: [
            {
                html: _('user_group_management'),
                id: 'modx-access-permissions-header',
                xtype: 'modx-header'
            }, MODx.getPageStructure(
                this.getPageTabs(config),
                { id: 'modx-access-permissions-tabs' }
            )
        ]
    });
    MODx.panel.GroupsRoles.superclass.constructor.call(this, config);

    if (MODx.perm.usergroup_user_list) {
        const
            userGrid = Ext.getCmp('modx-usergroup-users'),
            usergroupTree = Ext.getCmp('modx-tree-usergroup')
        ;
        if (usergroupTree) {
            usergroupTree.on({
                resize: {
                    fn: function(cmp) {
                        if (userGrid.hidden) {
                            Ext.getCmp('modx-tree-panel-usergroup').layout.west.getSplitBar().el.hide();
                        }
                    },
                    scope: this
                },
                refresh: {
                    fn: function() {
                        this.setActiveGroupNodeFromParam();
                    },
                    scope: this
                },
                click: {
                    fn: function(node, e) {
                        this.currentGroupId = MODx.util.tree.getGroupIdFromNode(node);
                        Ext.getCmp('modx-usergroup-users').clearGridFilters('filter-query-users');
                        if (this.currentGroupId > 0) {
                            MODx.util.url.setParams({
                                group: this.currentGroupId,
                                tab: 0
                            });
                        }
                        this.getUsers(node);
                    },
                    scope: this
                }
            });
        }

        usergroupTree.getLoader().on({
            load: {
                fn: function() {
                    this.currentGroupId = MODx.request.group || 0;
                    this.setActiveGroupNodeFromParam();
                },
                scope: this
            }
        });
    }
};
Ext.extend(MODx.panel.GroupsRoles, MODx.FormPanel, {
    getPageTabs: function(config) {
        const tabs = [];
        if (MODx.perm.usergroup_view && MODx.perm.usergroup_user_list) {
            tabs.push({
                title: `${_('user_groups')} & ${_('users')}`,
                autoHeight: true,
                layout: 'form',
                items: [{
                    html: `<p>${_('user_group_management_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    layout: 'border',
                    id: 'modx-tree-panel-usergroup',
                    height: 500,
                    border: false,
                    defaults: {
                        border: false,
                        bodyStyle: 'background-color:transparent;'
                    },
                    items: [
                        {
                            region: 'west',
                            cls: 'main-wrapper',
                            collapseMode: 'mini',
                            split: true,
                            useSplitTips: true,
                            monitorResize: true,
                            width: 280,
                            minWidth: 280,
                            minSize: 280,
                            maxSize: 400,
                            layout: 'fit',
                            items: [{
                                xtype: 'modx-tree-usergroup'
                            }]
                        }, {
                            region: 'center',
                            id: 'modx-usergroup-users',
                            xtype: 'modx-grid-user-group-users',
                            hidden: !(this.currentGroupId > 0),
                            usergroup: this.currentGroupId,
                            layout: 'fit',
                            cls: 'main-wrapper'
                        }
                    ]
                }]
            });
        }
        if (MODx.perm.view_role) {
            tabs.push({
                title: _('roles'),
                autoHeight: true,
                layout: 'form',
                items: [{
                    html: `<p>${_('roles_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-role',
                    cls: 'main-wrapper',
                    title: '',
                    preventRender: true
                }]
            });
        }
        if (MODx.perm.policy_view) {
            tabs.push({
                title: _('policies'),
                id: 'modx-panel-access-policies',
                autoHeight: true,
                layout: 'form',
                items: [{
                    html: `<p>${_('policy_management_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-access-policy',
                    cls: 'main-wrapper'
                }]
            });
        }
        if (MODx.perm.policy_template_view) {
            tabs.push({
                title: _('policy_templates'),
                id: 'modx-panel-access-policy-templates',
                autoHeight: true,
                layout: 'form',
                items: [{
                    html: `<p>${_('policy_templates.intro_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-access-policy-templates',
                    cls: 'main-wrapper'
                }]
            });
        }
        return tabs;
    },

    getUsers: function(node) {
        const userGrid = Ext.getCmp('modx-usergroup-users'),
              westPanel = Ext.getCmp('modx-tree-panel-usergroup').layout.west
        ;
        if (this.currentGroupId === 0) {
            userGrid.hide();
            westPanel.getSplitBar().el.hide();
        } else {
            userGrid.show();
            westPanel.getSplitBar().el.show();
            userGrid.usergroup = this.currentGroupId;
            userGrid.config.usergroup = this.currentGroupId;
            userGrid.store.baseParams.usergroup = this.currentGroupId;
            userGrid.store.load();
        }
    },

    setActiveGroupNodeFromParam: function() {
        if (this.currentGroupId > 0) {
            const usergroupTree = Ext.getCmp('modx-tree-usergroup'),
                  groupNodeId = `n_ug_${this.currentGroupId}`,
                  groupNode = usergroupTree.getNodeById(`n_ug_${this.currentGroupId}`)
            ;
            if (typeof groupNode !== 'undefined' && groupNodeId === groupNode.id) {
                groupNode.select();
                this.getUsers(groupNode);
            }
        }
    }
});
Ext.reg('modx-panel-groups-roles', MODx.panel.GroupsRoles);
