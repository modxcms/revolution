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
            xtype: 'portal'
            ,items: [{ 
                columnWidth: 1
                ,items: [{
                    title: _('general_information')
                    ,bodyStyle: 'padding: 1.5em;'
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
                        ,baseParams: {
                            action: 'getList'
                            ,addNone: true
                        }
                    }]
                },{
                    title: _('users')
                    ,items: [{
                        html: '<p>'+_('user_group_user_access_msg')+'</p>'
                    },{
                        xtype: 'modx-grid-user-group-users'
                        ,preventRender: true
                        ,usergroup: config.usergroup
                        ,autoHeight: true
                        ,listeners: {
                            'afterRemoveRow': {fn:function() {
                                this.fireEvent('fieldChange');
                            },scope:this}
                        }
                    }]
                },{
                    title: _('user_group_context_access')
                    ,items: [{
                        html: '<p>'+_('user_group_context_access_msg')+'</p>'
                    },{
                        xtype: 'modx-grid-user-group-context'
                        ,preventRender: true
                        ,usergroup: config.usergroup
                        ,autoHeight: true
                        ,listeners: {
                            'afterRemoveRow': {fn:function() {
                                this.fireEvent('fieldChange');
                            },scope:this}
                        }
                    }]
                },{
                    title: _('user_group_resourcegroup_access')
                    ,items: [{
                        html: '<p>'+_('user_group_resourcegroup_access_msg')+'</p>'
                    },{
                        xtype: 'modx-grid-user-group-resource-group'
                        ,preventRender: true
                        ,usergroup: config.usergroup
                        ,autoHeight: true
                        ,listeners: {
                            'afterRemoveRow': {fn:function() {
                                this.fireEvent('fieldChange');
                            },scope:this}
                        }
                    }]
                }]
            }]
        }]
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
        if (this.config.usergroup === '' || this.config.usergroup === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.usergroup
                ,getUsers: true
                ,getContexts: true
                ,getResourceGroups: true
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    Ext.get('modx-user-group-header').update('<h2>'+_('user_group')+': '+r.object.name+'</h2>');
                    
                    var d = Ext.decode(r.object.users);
                    var s = Ext.getCmp('modx-grid-user-group-users').getStore().loadData(d);
                    d = Ext.decode(r.object.contexts);
                    var s = Ext.getCmp('modx-grid-user-group-contexts').getStore().loadData(d);
                    d = Ext.decode(r.object.resourcegroups);
                    var s = Ext.getCmp('modx-grid-user-group-resource-groups').getStore().loadData(d);
                    this.fireEvent('ready',r.object);
                },scope:this}
            }
        });
    }
    
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            users: Ext.getCmp('modx-grid-user-group-users').encode()
            ,contexts: Ext.getCmp('modx-grid-user-group-contexts').encode()
            ,resource_groups: Ext.getCmp('modx-grid-user-group-resource-groups').encode()
        });
    }
    
    ,success: function(o) {
        Ext.getCmp('modx-grid-user-group-users').getStore().commitChanges();
        Ext.getCmp('modx-grid-user-group-contexts').getStore().commitChanges();
        Ext.getCmp('modx-grid-user-group-resource-groups').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-user-group',MODx.panel.UserGroup);


/**
 * Loads a grid of Users in a User Group.
 * 
 * @class MODx.grid.UserGroupUsers
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-group-users
 */
MODx.grid.UserGroupUsers = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: ''
        ,id: 'modx-grid-user-group-users'
        ,url: MODx.config.connectors_url+'security/group.php'
        ,fields: ['id','username','role','rolename']
        ,columns: [
            { header: _('id') ,dataIndex: 'id' ,width: 40 }
            ,{ header: _('username') ,dataIndex: 'username' ,width: 175 }
            ,{
                header: _('role')
                ,dataIndex: 'rolename'
                ,width: 175
            }
        ]
        ,tbar: [{
            text: _('user_group_user_add')
            ,handler: this.addUserToGroup
        }]
    });
    MODx.grid.UserGroupUsers.superclass.constructor.call(this,config);
    this.userRecord = new Ext.data.Record.create([{name: 'id'},{name: 'username'},{name:'role'
    },{name:'rolename'}]);
};
Ext.extend(MODx.grid.UserGroupUsers,MODx.grid.LocalGrid,{
    updateRole: function(btn,e) {
        var r = this.menu.record;
        r.usergroup = this.config.usergroup
        
        this.loadWindow(btn,e,{
            xtype: 'modx-window-user-group-role-update'
            ,record: r
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var rec = s.getAt(this.menu.recordIndex);
                    rec.set('role',r.role);
                    rec.set('rolename',r.rolename);
                    
                    Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange');
                },scope:this}
            }
        });
    }
    ,addUserToGroup: function(btn,e) {
        
        this.loadWindow(btn,e,{
            xtype: 'modx-window-user-group-adduser'
            ,record: {usergroup:this.config.usergroup}
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var rec = new this.userRecord(r);
                    s.add(rec);
                    
                    Ext.getCmp('modx-panel-user-group').fireEvent('fieldChange');
                },scope:this}
            }
        });
    }
    
    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        var m = this.menu;
        m.recordIndex = ri;
        m.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        m.removeAll();
        m.add({
            text: _('user_role_update')
            ,handler: this.updateRole
            ,scope: this
        },'-',{
            text: _('user_group_user_remove')
            ,handler: this.remove.createDelegate(this,[{text: _('user_group_user_remove_confirm')}])
            ,scope: this
        });
        m.show(e.target);
    }
});
Ext.reg('modx-grid-user-group-users',MODx.grid.UserGroupUsers);

MODx.window.UpdateUserGroupRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-window-user-group-role-update'
        ,title: _('user_group_user_update_role')
        ,url: MODx.config.connectors_url+'security/user.php'
        ,action: 'updateRole'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'usergroup'
            ,value: config.usergroup
        },{
            xtype: 'modx-combo-role'
            ,id: 'modx-uugr-role'
            ,name: 'role'
            ,fieldLabel: _('role')
        }]
    });
    MODx.window.UpdateUserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUserGroupRole,MODx.Window,{
    submit: function() {
        var r = this.fp.getForm().getValues();
        r.rolename = Ext.getCmp('modx-uugr-role').getRawValue();
        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-user-group-role-update',MODx.window.UpdateUserGroupRole);


MODx.window.AddUserToUserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'addUser'
        ,fields: [{
            fieldLabel: _('user')
            ,name: 'id'
            ,hiddenName: 'id'
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
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window,{
    submit: function() {
        var r = this.fp.getForm().getValues();
        
        var g = Ext.getCmp('modx-grid-user-group-users');
        var s = g.getStore();
        var v = s.query('id',r.id).items;
        if (v.length > 0) {
            MODx.msg.alert(_('error'),_('user_group_member_err_already_in'));
            return false;
        }
        
        r.rolename = Ext.getCmp('modx-auug-role').getRawValue();
        r.username = Ext.getCmp('modx-auug-user').getRawValue();
        this.fireEvent('success',r);
        this.hide();
        return false;
    }
});
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