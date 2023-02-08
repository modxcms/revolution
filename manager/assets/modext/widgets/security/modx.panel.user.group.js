/**
 * @class MODx.panel.UserGroup
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-user-group
 */
MODx.panel.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-user-group'
        ,cls: 'container form-with-labels'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Group/Update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [this.getPageHeader(config),{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: true
                ,bodyCssClass: 'tab-panel-wrapper'
                ,hideMode: 'offsets'
            }
            ,id: 'modx-usergroup-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,items: [{
                title: _('general_information')
                ,defaults: {
                    border: false
                    ,msgTarget: 'side'
                }
                ,layout: 'form'
                ,itemId: 'modx-usergroup-general-panel'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,items: [{
                    xtype: 'panel'
                    ,border: false
                    ,cls: 'main-wrapper'
                    ,layout: 'form'
                    ,items: [{
                        layout: 'column'
                        ,border: false
                        ,defaults: {
                            layout: 'form'
                            ,labelAlign: 'top'
                            ,labelSeparator: ''
                            ,anchor: '100%'
                            ,border: false
                        }
                        ,items: [{
                            columnWidth: .6
                            ,items: [{
                                xtype: 'hidden'
                                ,name: 'id'
                                ,id: 'modx-usergroup-id'
                                ,value: config.record.id
                            },{
                                name: 'name'
                                ,id: 'modx-usergroup-name'
                                ,xtype: config.record && (config.record.name === 'Administrator' || config.record.id === 0) ? 'statictextfield' : 'textfield'
                                ,fieldLabel: _('name')
                                ,allowBlank: false
                                ,enableKeyEvents: true
                                ,disabled: config.record.id === 0
                                ,anchor: '100%'
                                ,listeners: {
                                    'keyup': {scope:this,fn:function(f,e) {
                                        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-usergroup-name'
                                ,html: _('user_group_desc_name')
                                ,cls: 'desc-under'
                            },{
                                name: 'description'
                                ,id: 'modx-usergroup-description'
                                ,xtype: 'textarea'
                                ,fieldLabel: _('description')
                                ,anchor: '100%'
                                ,grow: true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-usergroup-description'
                                ,html: _('user_group_desc_description')
                                ,cls: 'desc-under'
                            }]
                        },{
                            columnWidth: .4
                            ,items: [{
                                name: 'parent'
                                ,hiddenName: 'parent'
                                ,id: 'modx-usergroup-parent'
                                ,xtype: 'modx-combo-usergroup'
                                ,fieldLabel: _('user_group_parent')
                                ,editable: false
                                ,anchor: '100%'
                                ,disabled: config.record.id === 0 || config.record.name === 'Administrator'
                                ,baseParams: {
                                    action: 'Security/Group/GetList'
                                    ,addNone: true
                                    ,exclude: config.record.id
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-usergroup-parent'
                                ,html: _('user_group_desc_parent')
                                ,cls: 'desc-under'
                            },{
                                name: 'dashboard'
                                ,id: 'modx-usergroup-dashboard'
                                ,xtype: 'modx-combo-dashboard'
                                ,fieldLabel: _('dashboard')
                                ,anchor: '100%'
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-usergroup-dashboard'
                                ,html: _('user_group_desc_dashboard')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                }]
            },{
                title: _('access_permissions')
                ,itemId: 'modx-usergroup-permissions-panel'
                ,items: [{
                    xtype: 'modx-vtabs'
                    ,items: [{
                        title: _('user_group_context_access')
                        ,itemId: 'user-group-context-access'
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,autoWidth: false
                        ,items: [{
                            html: '<p>'+_('user_group_context_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-context'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,cls: 'main-wrapper'
                            ,listeners: {
                                'afterRemoveRow': {fn:this.markDirty,scope:this}
                                ,'afteredit': {fn:this.markDirty,scope:this}
                                ,'updateAcl': {fn:this.markDirty,scope:this}
                                ,'createAcl': {fn:this.markDirty,scope:this}
                            }
                        }]
                    },{
                        title: _('user_group_resourcegroup_access')
                        ,itemId: 'user-group-resourcegroup-access'
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_resourcegroup_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-resource-group'
                            ,cls: 'main-wrapper'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,width: '97%'
                            ,listeners: {
                                'afterRemoveRow': {fn:this.markDirty,scope:this}
                                ,'afteredit': {fn:this.markDirty,scope:this}
                                ,'updateAcl': {fn:this.markDirty,scope:this}
                                ,'createAcl': {fn:this.markDirty,scope:this}
                            }
                        }]
                    },{
                        title: _('user_group_category_access')
                        ,itemId: 'user-group-category-access'
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_category_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-category'
                            ,cls: 'main-wrapper'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,width: '97%'
                            ,listeners: {
                                'afterRemoveRow': {fn:this.markDirty,scope:this}
                                ,'afteredit': {fn:this.markDirty,scope:this}
                                ,'updateAcl': {fn:this.markDirty,scope:this}
                                ,'createAcl': {fn:this.markDirty,scope:this}
                            }
                        }]
                    },{
                        title: _('user_group_source_access')
                        ,itemId: 'user-group-source-access'
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_source_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-source'
                            ,cls: 'main-wrapper'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,width: '97%'
                            ,listeners: {
                                'afterRemoveRow': {fn:this.markDirty,scope:this}
                                ,'afteredit': {fn:this.markDirty,scope:this}
                                ,'updateAcl': {fn:this.markDirty,scope:this}
                                ,'createAcl': {fn:this.markDirty,scope:this}
                            }
                        }]
                    },{
                        title: _('user_group_namespace_access')
                        ,itemId: 'user-group-namespace-access'
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>' + _('user_group_namespace_access_desc') + '</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-namespace'
                            ,cls: 'main-wrapper'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,width: '97%'
                        }]
                    }]
                    ,listeners: {
                        render: function(vtabPanel) {
                            var elCatsPanelKey = vtabPanel.items.keys.indexOf('user-group-category-access'),
                                mediaSrcPanelKey = vtabPanel.items.keys.indexOf('user-group-source-access'),
                                namespacePanelKey = vtabPanel.items.keys.indexOf('user-group-namespace-access')
                                form = Ext.getCmp('modx-panel-user-group').getForm()
                                ;
                            if (form.record.id === 0) {
                                vtabPanel.hideTabStripItem(elCatsPanelKey);
                                vtabPanel.hideTabStripItem(mediaSrcPanelKey);
                                vtabPanel.hideTabStripItem(namespacePanelKey);
                            }
                        }
                    }
                }]
            },{
                title: _('users')
                ,itemId: 'modx-usergroup-users-panel'
                ,items: [{
                    html: '<p>'+_('user_group_user_access_msg')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'modx-grid-user-group-users'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                    ,usergroup: config.record.id
                    ,autoHeight: true
                    ,width: '97%'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                        ,'updateRole': {fn:this.markDirty,scope:this}
                        ,'addUser': {fn:this.markDirty,scope:this}
                    }
                }]
            },{
                title: _('settings')
                ,itemId: 'modx-usergroup-settings-panel'
                ,layout: 'form'
                ,items: [{
                    html: '<p>'+_('user_group_settings_desc')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'modx-grid-group-settings'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                    ,group: config.record.id
                    ,autoHeight: true
                    ,width: '97%'
                }]
            }]
            ,listeners: {
                render: function(tabPanel) {
                    var usersPanelKey = tabPanel.items.keys.indexOf('modx-usergroup-users-panel'),
                        settingsPanelKey = tabPanel.items.keys.indexOf('modx-usergroup-settings-panel'),
                        form = Ext.getCmp('modx-panel-user-group').getForm()
                        ;
                    if (form.record.id === 0) {
                        tabPanel.hideTabStripItem(usersPanelKey);
                        tabPanel.hideTabStripItem(settingsPanelKey);
                    }
                    if (!MODx.perm.usergroup_user_list) {
                        tabPanel.hideTabStripItem(usersPanelKey);
                    }
                }
            }
        }]
        ,useLoadingMask: false
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.UserGroup,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized || this.config.usergroup === '' || this.config.usergroup == undefined) {
            this.fireEvent('ready');
            return false;
        }
        var r = this.config.record;
        this.getForm().setValues(r);
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(r.name));

        this.fireEvent('ready',r);
        MODx.fireEvent('ready');
        this.initialized = true;
    }
    ,beforeSubmit: function(o) {}
    ,success: function(o) {}
    ,getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-user-group-header', [{
            text: _('user_group_management'),
            href: MODx.getPage('security/permission')
        }]);
    }
});
Ext.reg('modx-panel-user-group',MODx.panel.UserGroup);

/**
 * @class MODx.grid.FCProfileUserGroups
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-users
 */
MODx.grid.UserGroupUsers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: ''
        ,id: 'modx-grid-user-group-users'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Group/User/GetList'
            ,usergroup: config.usergroup
            ,username: MODx.request.username ? decodeURIComponent(MODx.request.username) : ''
        }
        ,paging: true
        ,grouping: true
        ,remoteSort: true
        ,groupBy: 'role_name'
        ,singleText: _('user')
        ,pluralText: _('users')
        ,sortBy: 'authority'
        ,sortDir: 'ASC'
        ,fields: [
            'id',
            'username',
            'role',
            'role_name',
            'authority'
        ]
        ,columns: [{
            header: _('username')
            ,dataIndex: 'username'
            ,width: 175
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/user/update&id=' + record.data.id
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('role')
            ,dataIndex: 'role_name'
            ,width: 175
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/permission'
                    ,target: '_blank'
                });
            }, scope: this }
        }]
        ,tbar: [{
            text: _('user_group_update')
            ,cls: 'primary-button'
            ,handler: this.updateUserGroup
            ,hidden: (MODx.perm.usergroup_edit == 0 || config.ownerCt.id != 'modx-tree-panel-usergroup')
        },{
            text: _('user_group_user_add')
            ,cls: 'primary-button'
            ,handler: this.addUser
            ,hidden: MODx.perm.usergroup_user_edit == 0
        },'->',{
            xtype: 'textfield'
            ,itemId: 'filter-username'
            ,emptyText: _('search')
            ,value: MODx.request.username ? decodeURIComponent(MODx.request.username) : ''
            ,listeners: {
                change: {
                    fn: function (cmp, newValue, oldValue) {
                        this.applyGridFilter(cmp, 'username');
                    },
                    scope: this
                },
                afterrender: {
                    fn: function(cmp) {
                        if (MODx.request.query) {
                            this.applyGridFilter(cmp, 'username');
                        }
                    },
                    scope: this
                },
                render: {
                    fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: this.blur,
                            scope: cmp
                        });
                    }
                    ,scope: this
                }
            }
        },{
            text: _('filter_clear')
            ,itemId: 'filter-clear'
            ,listeners: {
                click: {
                    fn: function() {
                        this.clearGridFilters('filter-username');
                    },
                    scope: this
                },
                mouseout: {
                    fn: function(evt) {
                        this.removeClass('x-btn-focus');
                    }
                }
            }
        }]
    });
    MODx.grid.UserGroupUsers.superclass.constructor.call(this,config);
    this.addEvents('updateRole','addUser');
};
Ext.extend(MODx.grid.UserGroupUsers,MODx.grid.Grid,{
    getMenu: function() {
        var m = [];
        if (MODx.perm.usergroup_user_edit) {
            m.push({
                text: _('user_role_update')
                ,handler: this.updateRole
            });
            m.push('-');
            m.push({
                text: _('user_group_user_remove')
                ,handler: this.removeUser
            });
        }
        return m;
    }

    ,updateUserGroup: function() {
        var id = this.config.usergroup;
        MODx.loadPage('security/usergroup/update', 'id=' + id);
    }

    ,updateRole: function(btn,e) {
        var r = this.menu.record;
        r.usergroup = this.config.usergroup;
        r.user = r.id;

        this.loadWindow(btn,e,{
            xtype: 'modx-window-user-group-role-update'
            ,record: r
            ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                    this.fireEvent('updateRole',r);
                },scope:this}
            }
        });
    }

    ,addUser: function(btn,e) {
        var r = {usergroup:this.config.usergroup};

        if (!this.windows['modx-window-user-group-adduser']) {
            this.windows['modx-window-user-group-adduser'] = Ext.ComponentMgr.create({
                xtype: 'modx-window-user-group-adduser'
                ,record: r
                ,grid: this
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('addUser',r);
                    },scope:this}
                }
            });
        }

        this.windows['modx-window-user-group-adduser'].setValues(r);
        this.windows['modx-window-user-group-adduser'].show(e.target);
    }

    ,removeUser: function(btn,e) {
        var r = this.menu.record;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('user_group_user_remove_confirm') || _('confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'Security/Group/User/Remove'
                ,user: r.id
                ,usergroup: this.config.usergroup
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-user-group-users',MODx.grid.UserGroupUsers);

/**
 * @class MODx.window.UpdateUserGroupRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-role-update
 */
MODx.window.UpdateUserGroupRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-window-user-group-role-update'
        ,title: _('user_group_user_update_role')
        ,url: MODx.config.connector_url
        ,action: 'Security/Group/User/Update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'usergroup'
            ,value: config.usergroup
        },{
            xtype: 'hidden'
            ,name: 'user'
            ,value: config.user
        },{
            xtype: 'modx-combo-usergrouprole'
            ,id: 'modx-uugr-role'
            ,name: 'role'
            ,fieldLabel: _('role')
        }]
    });
    MODx.window.UpdateUserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUserGroupRole,MODx.Window);
Ext.reg('modx-window-user-group-role-update',MODx.window.UpdateUserGroupRole);

/**
 * @class MODx.window.AddUserToUserGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-adduser
 */
MODx.window.AddUserToUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'auug'+Ext.id();
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        ,url: MODx.config.connector_url
        ,action: 'Security/Group/User/Create'
        ,fields: [{
            fieldLabel: _('user')
            ,description: MODx.expandHelp ? '' : _('user_group_user_add_user_desc')
            ,name: 'user'
            ,hiddenName: 'user'
            ,id: 'modx-auug-user'
            ,xtype: 'modx-combo-user'
            ,editable: true
            ,typeAhead: true
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-user'
            ,html: _('user_group_user_add_user_desc')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('role')
            ,description: MODx.expandHelp ? '' : _('user_group_user_add_role_desc')
            ,name: 'role'
            ,hiddenName: 'role'
            ,id: 'modx-auug-role'
            ,xtype: 'modx-combo-role'
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-role'
            ,html: _('user_group_user_add_role_desc')
            ,cls: 'desc-under'
        },{
            name: 'usergroup'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('modx-window-user-group-adduser',MODx.window.AddUserToUserGroup);
