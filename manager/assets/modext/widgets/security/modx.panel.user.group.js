MODx.panel.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-user-group'
        ,url: MODx.config.connectors_url+'security/group.php'
        ,baseParams: {
            action: 'update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{ 
             html: '<h2>'+_('user_group_new')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-user-group-header'
        },{            
            xtype: 'modx-tabs'
            ,defaults: { 
                bodyStyle: 'padding: 15px;'
                ,autoHeight: true
                ,border: true
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
                ,bodyStyle: 'padding: 15px;'
                ,defaults: { border: false ,msgTarget: 'side' }
                ,layout: 'form'
                ,id: 'modx-chunk-form'
                ,labelWidth: 150
                ,items: [{
                    html: '<p>'+''+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'modx-usergroup-id'
                    ,value: config.usergroup
                },{
                    name: 'name'
                    ,id: 'modx-usergroup-name'
                    ,xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,allowBlank: false
                    ,enableKeyEvents: true
                    ,disabled: config.usergroup === 0 ? true : false
                    ,width: 300
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('modx-user-group-header').getEl().update('<h2>'+_('user_group')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    name: 'parent'
                    ,hiddenName: 'parent'
                    ,id: 'modx-usergroup-parent'
                    ,xtype: 'modx-combo-usergroup'
                    ,fieldLabel: _('user_group_parent')
                    ,editable: false
                    ,width: '97%'
                    ,disabled: config.usergroup === 0 ? true : false
                    ,baseParams: {
                        action: 'getList'
                        ,addNone: true
                    }
                }]
            },{
                title: _('users')
                ,hidden: config.usergroup == 0 ? true : false
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('user_group_user_access_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-group-users'
                    ,preventRender: true
                    ,usergroup: config.usergroup
                    ,autoHeight: true
                    ,width: '97%'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                        ,'updateRole': {fn:this.markDirty,scope:this}
                        ,'addMember': {fn:this.markDirty,scope:this}
                    }
                }]
            },{
                title: _('user_group_context_access')
                ,hidden: config.usergroup === 0 ? true : false
                ,forceLayout: true
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('user_group_context_access_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-group-context'
                    ,preventRender: true
                    ,usergroup: config.usergroup
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
                title: _('user_group_resourcegroup_access')
                ,hidden: config.usergroup === 0 ? true : false
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('user_group_resourcegroup_access_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-group-resource-group'
                    ,preventRender: true
                    ,usergroup: config.usergroup
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
                ,hidden: config.usergroup === 0 ? true : false
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('user_group_category_access_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-user-group-category'
                    ,preventRender: true
                    ,usergroup: config.usergroup
                    ,autoHeight: true
                    ,width: '97%'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                        ,'afteredit': {fn:this.markDirty,scope:this}
                        ,'updateAcl': {fn:this.markDirty,scope:this}
                        ,'createAcl': {fn:this.markDirty,scope:this}
                    }
                }]
            }]
        }]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.UserGroup,MODx.FormPanel,{
    setup: function() {
        if (this.config.usergroup === '' || this.config.usergroup == undefined) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.usergroup
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-user-group-header').update('<h2>'+_('user_group')+': '+r.object.name+'</h2>');
                                        
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
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
        ,url: MODx.config.connectors_url+'security/usergroup/user.php'
        ,baseParams: {
            action: 'getList'
            ,usergroup: config.usergroup
        }
        ,paging: true
        ,fields: ['id','username','role','role_name','menu']
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('username') ,dataIndex: 'username' ,width: 175 }
            ,{
                header: _('role')
                ,dataIndex: 'role'
                ,width: 175
            }
        ]
        ,tbar: [{
            text: _('user_group_user_add')
            ,handler: this.addMember
        },'->',{
            xtype: 'textfield'
            ,id: 'modx-ugu-filter-username'
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
            ,handler: this.clearFilter
            ,scope: this
        }]
    });
    MODx.grid.UserGroupUsers.superclass.constructor.call(this,config);
    this.addEvents('updateRole','addMember');
};
Ext.extend(MODx.grid.UserGroupUsers,MODx.grid.Grid,{
    
    searchUser: function(tf,nv,ov) {
        this.getStore().baseParams['username'] = Ext.getCmp('modx-ugu-filter-username').getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,clearFilter: function(btn,e) {
        Ext.getCmp('modx-ugu-filter-username').setValue('');
        this.getStore().baseParams['username'] = '';
        this.getBottomToolbar().changePage(1);
        this.refresh();
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
        this.loadWindow(btn,e,{
            xtype: 'modx-window-user-group-adduser'
            ,record: {usergroup:this.config.usergroup}
            ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                    this.fireEvent('addMember',r);
                },scope:this}
            }
        });
    }
    ,removeUser: function(btn,e) {
        var r = this.menu.record;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('user_group_user_remove_confirm') || _('confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'remove'
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
        ,url: MODx.config.connectors_url+'security/usergroup/user.php'
        ,action: 'update'
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
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/usergroup/user.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('user')
            ,name: 'user'
            ,hiddenName: 'user'
            ,id: 'modx-auug-user'
            ,xtype: 'modx-combo-user'
            ,editable: false
            ,allowBlank: false
        },{
            fieldLabel: _('role')
            ,name: 'role'
            ,hiddenName: 'role'
            ,id: 'modx-auug-role'
            ,xtype: 'modx-combo-role'
            ,allowBlank: false
        },{
            name: 'usergroup'
            ,xtype: 'hidden'
        }]
    });
    MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('modx-window-user-group-adduser',MODx.window.AddUserToUserGroup);



MODx.combo.Authority = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'authority'
        ,hiddenName: 'authority'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/role.php'
        ,baseParams: { action: 'getAuthorityList', addNone: true }
    });
    MODx.combo.Authority.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Authority,MODx.combo.ComboBox);
Ext.reg('modx-combo-authority',MODx.combo.Authority);