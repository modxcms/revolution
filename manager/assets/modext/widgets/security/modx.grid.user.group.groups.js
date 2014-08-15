/**
 * Loads a grid of groups
 * 
 * @class MODx.grid.UserGroupGroups
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-group-groups
 */
MODx.grid.UserGroupGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: ''
        ,id: 'modx-grid-user-group-groups'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/getlist'
            ,combo: true
        }
        ,paging: true
        ,fields: ['id','name','description','parent','rank']
        ,cls: 'modx-grid'
        ,sortDir: 'ASC'
        ,remoteSort: true
        ,autoExpandColumn: 'description'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 20
        },{
            header: _('parent')
            ,dataIndex: 'parent'
            ,width: 20
        },{
            header: _('user_group')
            ,dataIndex: 'name'
        },{
            header: _('description')
            ,dataIndex: 'description'
        }]
        ,tbar: [{
            text: _('user_group_new')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.createUserGroup.createDelegate(this,[true],true)
        },'->',{
            xtype: 'modx-combo-usergroup'
            ,name: 'parent-group'
            ,id: 'modx-group-filter-parent'
            ,itemId: 'parent-usergroup'
            ,emptyText: _('parent')+'...'
            ,baseParams: {
                action: 'security/group/getList'
                ,addAll: true
            }
            ,typeAhead: true
            ,editable: true
            ,width: 200
            ,listeners: {
                'select': {fn:this.filterParentUsergroup,scope:this}
            }
        },{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-group-search'
            ,emptyText: _('search')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.UserGroupGroups.superclass.constructor.call(this,config);
    this.userRecord = new Ext.data.Record.create(['usergroup','name','member','role','rolename','primary_group']);
    this.addEvents('beforeUpdateRole','afterUpdateRole','beforeAddGroup','afterAddGroup','beforeReorderGroup','afterReorderGroup');
};
Ext.extend(MODx.grid.UserGroupGroups,MODx.grid.Grid,{
    windows: {}
    
    ,filterParentUsergroup: function(cb,nv,ov) {
        this.getStore().baseParams.parent = Ext.isEmpty(nv) || Ext.isObject(nv) ? cb.getValue() : nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    
    ,search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var m = [];
        if (MODx.perm.usergroup_user_edit) {
            m.push({
                text: _('user_group_user_add')
                ,handler: this.addUser
            });
            m.push('-');
        }
        if (MODx.perm.usergroup_new) {
            m.push({
                text: _('user_group_create')
                ,handler: this.createUserGroup
            });
        }
        if (MODx.perm.usergroup_edit) {
            m.push({
                text: _('user_group_update')
                ,handler: this.updateUserGroup
            });
        }
        if (MODx.perm.usergroup_delete) {
            m.push('-');
            m.push({
                text: _('user_group_remove')
                ,handler: this.removeUserGroup
            });
        }
        
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
    
    ,addUser: function(item,e) {
        var r = this.menu.record;
        var adduserWin = MODx.load({
            xtype: 'modx-window-usergroup-adduser'
            ,record: {usergroup: r.id ? r.id : 0}
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        adduserWin.reset();
        adduserWin.setValues(r);
        adduserWin.show(e.target);
    }

    ,createUserGroup: function(item,e,tbar) {
        tbar = tbar || false;
        var p, r = this.menu.record;
        if (tbar === false) {
            p = r.id ? r.id : 0;
        } else {p = 0;}
        var createUsergroupWin = MODx.load({
            xtype: 'modx-window-usergroup-create'
            ,record: {'parent': p}
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        createUsergroupWin.reset();
        createUsergroupWin.show(e.target);
    }

    ,updateUserGroup: function(item,e) {
        var r = this.menu.record;
        MODx.loadPage('security/usergroup/update', 'id=' + r.id);
    }
    
    ,removeUserGroup: function(item,e) {
        var r = this.menu.record;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('user_group_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/group/remove'
                ,id: r.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,clearFilter: function() {
        this.getStore().baseParams = {
            action: 'security/group/getlist'
            ,combo: true
        };
        Ext.getCmp('modx-group-search').reset();
        Ext.getCmp('modx-group-filter-parent').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('modx-grid-user-group-groups',MODx.grid.UserGroupGroups);

MODx.window.CreateUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'cugrp'+Ext.id();
    console.log('config', config);
    Ext.applyIf(config,{
        title: _('create_user_group')
        ,id: this.ident
        // ,height: 150
        ,width: 700
        ,stateful: false
        ,url: MODx.config.connector_url
        ,action: 'security/group/create'
        ,fields: [{
            name: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'hidden'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('user_group_desc_name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('user_group_desc_name')
            ,cls: 'desc-under'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,description: MODx.expandHelp ? '' : _('user_group_desc_description')
            ,name: 'description'
            ,id: 'modx-'+this.ident+'-description'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-description'
            ,html: _('user_group_desc_description')
            ,cls: 'desc-under'
        },{
            xtype: 'fieldset'
            ,collapsible: true
            ,collapsed: false
            ,autoHeight: true
            ,title: _('user_group_aw')
            ,items: [{
                html: '<p style="margin: 5px 0 0">'+_('user_group_aw_desc')+'</p>'
                ,cls: 'desc-under'
            },{
                layout: 'column'
                ,border: false
                ,defaults: {
                    layout: 'form'
                    ,labelAlign: 'top'
                    ,anchor: '100%'
                    ,border: false
                }
                ,items: [{
                    columnWidth: .5
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'aw_users'
                        ,fieldLabel: _('user_group_aw_users')
                        ,description: _('user_group_aw_users_desc')
                        ,id: this.ident+'-aw-users'
                        ,anchor: '100%'
                        ,value: ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-users'
                        ,html: _('user_group_aw_users_desc')
                        ,cls: 'desc-under'
                    },{
                        fieldLabel: _('user_group_aw_resource_groups')
                        ,description: _('user_group_aw_resource_groups_desc')
                        ,name: 'aw_resource_groups'
                        ,id: this.ident+'-aw-resource-groups'
                        ,xtype: 'textfield'
                        ,value: ''
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-resource-groups'
                        ,html: _('user_group_aw_resource_groups_desc')
                        ,cls: 'desc-under'

                    },{
                        boxLabel: _('user_group_aw_parallel')
                        ,description: _('user_group_aw_parallel_desc')
                        ,name: 'aw_parallel'
                        ,id: this.ident+'-aw-parallel'
                        ,xtype: 'checkbox'
                        ,checked: false
                        ,inputValue: 1
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-parallel'
                        ,html: _('user_group_aw_parallel_desc')
                        ,cls: 'desc-under'
                    }]
                },{
                    columnWidth: .5
                    ,items: [{
                        xtype: 'textfield'
                        ,name: 'aw_contexts'
                        ,fieldLabel: _('contexts')
                        ,description: MODx.expandHelp ? '' : _('user_group_aw_contexts_desc')
                        ,id: this.ident+'-aw-contexts'
                        ,anchor: '100%'
                        ,value: 'web'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-contexts'
                        ,html: _('user_group_aw_contexts_desc')
                        ,cls: 'desc-under'

                    },{
                        xtype: 'modx-combo-policy'
                        ,baseParams: {
                            action: 'security/access/policy/getList'
                            ,group: 'Admin'
                            ,combo: '1'
                        }
                        ,name: 'aw_manager_policy'
                        ,fieldLabel: _('user_group_aw_manager_policy')
                        ,description: MODx.expandHelp ? '' : _('user_group_aw_manager_policy_desc')
                        ,id: this.ident+'-aw-manager-policy'
                        ,anchor: '100%'
                        ,allowBlank: true
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-manager-policy'
                        ,html: _('user_group_aw_manager_policy_desc')
                        ,cls: 'desc-under'

                    },{
                        fieldLabel: _('user_group_aw_categories')
                        ,description: _('user_group_aw_categories_desc')
                        ,name: 'aw_categories'
                        ,id: this.ident+'-aw-categories'
                        ,xtype: 'textfield'
                        ,value: ''
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-aw-categories'
                        ,html: _('user_group_aw_categories_desc')
                        ,cls: 'desc-under'

                    }]
                }]
            }]
        }]
        ,keys: []
    });
    MODx.window.CreateUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-create',MODx.window.CreateUserGroup);

MODx.window.AddUserToUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'adtug'+Ext.id();
    Ext.applyIf(config,{
        title: _('user_group_user_add')
        ,id: this.ident
        // ,height: 150
        // ,width: 375
        ,url: MODx.config.connector_url
        ,action: 'security/group/user/create'
        ,fields: [{
            fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('user_group_user_add_user_desc')
            ,name: 'user'
            ,hiddenName: 'user'
            ,xtype: 'modx-combo-user'
            ,editable: true
            ,typeAhead: true
            ,allowBlank: false
            ,id: 'modx-'+this.ident+'-user'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-role'
            ,html: _('user_group_user_add_user_desc')
            ,cls: 'desc-under'
        },{
            fieldLabel: _('role')
            ,description: MODx.expandHelp ? '' : _('user_group_user_add_role_desc')
            ,name: 'role'
            ,hiddenName: 'role'
            ,xtype: 'modx-combo-role'
            ,id: 'modx-'+this.ident+'-role'
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
            ,id: 'modx-'+this.ident+'-user-group'
        }]
    });
    MODx.window.AddUserToUserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddUserToUserGroup,MODx.Window);
Ext.reg('modx-window-usergroup-adduser',MODx.window.AddUserToUserGroup);
