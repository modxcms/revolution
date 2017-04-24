MODx.panel.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-user-group'
		,cls: 'container form-with-labels'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: _('user_group_new')
            ,id: 'modx-user-group-header'
            ,xtype: 'modx-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: true
                ,bodyCssClass: 'tab-panel-wrapper'
            }
            ,id: 'modx-usergroup-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-usergroup-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                title: _('general_information')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,layout: 'form'
                ,id: 'modx-usergroup-form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,items: [{
					xtype: 'panel'
					,border: false
					,cls:'main-wrapper'
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
                                ,xtype: config.record && (config.record.name == 'Administrator' || config.record.id == 0) ? 'statictextfield' : 'textfield'
                                ,fieldLabel: _('name')+'<span class="required">*</span>'
                                ,allowBlank: false
                                ,enableKeyEvents: true
                                ,disabled: config.record.id === 0
                                ,anchor: '100%'
                                ,listeners: {
                                    'keyup': {scope:this,fn:function(f,e) {
                                        Ext.getCmp('modx-user-group-header').getEl().update('<h2>'+_('user_group')+': '+Ext.util.Format.htmlEncode(f.getValue())+'</h2>');
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
                                ,disabled: config.record.id === 0
                                ,baseParams: {
                                    action: 'security/group/getList'
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
                title: _('users')
                ,hideMode: 'offsets'
				,layout: 'form'
				,id: 'modx-usergroup-users-panel'
                ,items: [{
                    html: '<p>'+_('user_group_user_access_msg')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'modx-grid-user-group-users'
					,cls:'main-wrapper'
                    ,preventRender: true
                    ,usergroup: config.record.id
                    ,autoHeight: true
                    ,width: '97%'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                        ,'updateRole': {fn:this.markDirty,scope:this}
                        ,'addMember': {fn:this.markDirty,scope:this}
                    }
                }]
            },{
                title: _('settings')
                ,forceLayout: true
                ,hideMode: 'offsets'
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
//                    ,listeners: {
//                        'afterAutoSave':{fn:this.markDirty,scope:this}
//                    }
                }]
            },{
                title: _('permissions')
                ,hidden: config.record.id === 0
                ,forceLayout: true
                ,hideMode: 'offsets'
                ,items: [{
                    xtype: 'modx-vtabs'
                    ,items: [{
                        title: _('user_group_context_access')
                        ,forceLayout: true
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_context_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-context'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,cls:'main-wrapper'
                            ,listeners: {
                                'afterRemoveRow': {fn:this.markDirty,scope:this}
                                ,'afteredit': {fn:this.markDirty,scope:this}
                                ,'updateAcl': {fn:this.markDirty,scope:this}
                                ,'createAcl': {fn:this.markDirty,scope:this}
                            }
                        }]
                    },{
                        title: _('user_group_resourcegroup_access')
                        ,hidden: config.record.id === 0
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_resourcegroup_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-resource-group'
                            ,cls:'main-wrapper'
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
                        ,hidden: config.record.id === 0
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_category_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-category'
                            ,cls:'main-wrapper'
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
                        ,hidden: config.record.id === 0
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>'+_('user_group_source_access_msg')+'</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-source'
                            ,cls:'main-wrapper'
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
                        ,hidden: config.record.id === 0
                        ,hideMode: 'offsets'
                        ,layout: 'form'
                        ,items: [{
                            html: '<p>' + _('user_group_namespace_access_desc') + '</p>'
                            ,xtype: 'modx-description'
                        },{
                            xtype: 'modx-grid-user-group-namespace'
                            ,cls:'main-wrapper'
                            ,preventRender: true
                            ,usergroup: config.record.id
                            ,autoHeight: true
                            ,width: '97%'
                            //,listeners: {
                            //    'afterRemoveRow': {fn:this.markDirty,scope:this}
                            //    ,'afteredit': {fn:this.markDirty,scope:this}
                            //    ,'updateAcl': {fn:this.markDirty,scope:this}
                            //    ,'createAcl': {fn:this.markDirty,scope:this}
                            //}
                        }]
                    }]
                }]
            }]
        }]
        ,useLoadingMask: false
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.UserGroup.superclass.constructor.call(this,config);
    if (config.record.id == 0 || MODx.perm.usergroup_user_list == 0) {
        var tbs = Ext.getCmp('modx-usergroup-tabs');
        tbs.hideTabStripItem('modx-usergroup-users-panel');
    }
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
        Ext.defer(function() {
            Ext.get('modx-user-group-header').update('<h2>'+_('user_group')+': '+Ext.util.Format.htmlEncode(r.name)+'</h2>');
        }, 250, this);

        this.fireEvent('ready',r);
        MODx.fireEvent('ready');
        this.initialized = true;
    }
    ,beforeSubmit: function(o) {}
    ,success: function(o) {}
});
Ext.reg('modx-panel-user-group',MODx.panel.UserGroup);

MODx.grid.UserGroupUsers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: ''
        ,id: 'modx-grid-user-group-users'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/user/getList'
            ,usergroup: config.usergroup
        }
        ,paging: true
        ,grouping: true
        ,remoteSort: true
        ,groupBy: 'role_name'
        ,singleText: _('user')
        ,pluralText: _('users')
        ,sortBy: 'authority'
        ,sortDir: 'ASC'
        ,fields: ['id','username','role','role_name','authority']
        ,columns: [{
            header: _('username')
            ,dataIndex: 'username'
            ,width: 175
            ,sortable: true
        },{
            header: _('role')
            ,dataIndex: 'role_name'
            ,width: 175
            ,sortable: true
        }]
        ,tbar: [{
            text: _('user_group_user_add')
            ,cls:'primary-button'
            ,handler: this.addMember
            ,hidden: MODx.perm.usergroup_user_edit == 0
        },'->',{
            xtype: 'textfield'
            ,id: 'modx-ugu-filter-username'
            ,cls: 'x-form-filter'
            ,listeners: {
                'change': {fn:this.searchUser,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true; }
                        ,scope: cmp
                    });
                }}
            }
            ,emptyText: _('search')
            ,scope: this
        },{
            text: _('clear_filter')
            ,id: 'modx-ugu-clear-filter'
            ,cls: 'x-form-filter-clear'
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupUsers.superclass.constructor.call(this,config);
    this.addEvents('updateRole','addMember');
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
    ,searchUser: function(tf,nv,ov) {
        this.getStore().baseParams['username'] = Ext.getCmp('modx-ugu-filter-username').getValue();
        this.getBottomToolbar().changePage(1);
        //this.refresh();
    }

    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugu-filter-username').setValue('');
        this.getStore().baseParams['username'] = '';
        this.getBottomToolbar().changePage(1);
        //this.refresh();
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
    ,addMember: function(btn,e) {
        var r = {usergroup:this.config.usergroup};

        if (!this.windows['modx-window-user-group-adduser']) {
            this.windows['modx-window-user-group-adduser'] = Ext.ComponentMgr.create({
                xtype: 'modx-window-user-group-adduser'
                ,record: r
                ,grid: this
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('addMember',r);
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
                action: 'security/group/user/remove'
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

MODx.window.UpdateUserGroupRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-window-user-group-role-update'
        ,title: _('user_group_user_update_role')
        ,url: MODx.config.connector_url
        ,action: 'security/group/user/update'
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


MODx.window.AddUserToUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'auug'+Ext.id();
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        // ,height: 150
        // ,width: 500
        ,url: MODx.config.connector_url
        ,action: 'security/group/user/create'
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
