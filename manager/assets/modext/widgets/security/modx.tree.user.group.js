/**
 * Generates the User Group Tree
 *
 * @class MODx.tree.UserGroup
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-usergroup
 */
MODx.tree.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_groups')
        ,id: 'modx-tree-usergroup'
        ,url: MODx.config.connector_url
        ,action: 'security/group/getnodes'
        ,sortAction: 'security/group/sort'
        ,root_id: 'n_ug_0'
        ,root_name: _('user_groups')
        ,enableDrag: true
        ,enableDrop: true
        ,rootVisible: true
        ,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,tbar: [{
            text: _('user_group_new')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.createUserGroup.createDelegate(this,[true],true)
        }]
    });
    MODx.tree.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.UserGroup,MODx.tree.Tree,{
    windows: {}

    /**
     * Handles tree clicks
     * @param {Object} n The node clicked
     * @param {Object} e The event object
     */
    ,_handleClick: function (n,e) {
        e.stopEvent();
        e.preventDefault();
        
        if (this.disableHref) {return true;}
        if (e.ctrlKey) {return true;}
        return true;
    }
    
    ,addUser: function(item,e) {
        var n = this.cm.activeNode;
        var ug = n.id.substr(2).split('_');ug = ug[1];
        if (ug === undefined) {ug = 0;}
        var r = {usergroup: ug};

        if (!this.windows.adduser) {
            this.windows.adduser = MODx.load({
                xtype: 'modx-window-usergroup-adduser'
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.windows.adduser.reset();
        }
        this.windows.adduser.setValues(r);
        this.windows.adduser.show(e.target);
    }

    ,createUserGroup: function(item,e,tbar) {
        tbar = tbar || false;
        var p;
        if (tbar === false) {
            var n = this.cm.activeNode;
            p = n.id.substr(2).split('_');p = p[1];
            if (p === undefined) {p = 0;}
        } else {p = 0;}

        var r = {'parent': p};

        if (!this.windows.createUsergroup) {
            this.windows.createUsergroup = MODx.load({
                xtype: 'modx-window-usergroup-create'
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.windows.createUsergroup.reset();
        }
        this.windows.createUsergroup.fp.form.setValues(r);
        this.windows.createUsergroup.show(e.target);
    }

    ,updateUserGroup: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_');id = id[1];

        MODx.loadPage('security/usergroup/update', 'id=' + id);
    }

    ,getMenu: function() {
        var m = [];
        var n = this.cm.activeNode;
        var ui = n.getUI();

        switch (n.attributes.type) {
            case 'usergroup':
                if (MODx.perm.usergroup_user_edit && ui.hasClass('padduser')) {
                    m.push({
                        text: _('user_group_user_add')
                        ,handler: this.addUser
                    });
                    m.push('-');
                }
                if (MODx.perm.usergroup_new && ui.hasClass('pcreate')) {
                    m.push({
                        text: _('user_group_create')
                        ,handler: this.createUserGroup
                    });
                }
                if (MODx.perm.usergroup_edit && ui.hasClass('pupdate')) {
                    m.push({
                        text: _('user_group_update')
                        ,handler: this.updateUserGroup
                    });
                }
                if (MODx.perm.usergroup_delete && ui.hasClass('premove')) {
                    m.push('-');
                    m.push({
                        text: _('user_group_remove')
                        ,handler: this.removeUserGroup
                    });
                }
                break;
            case 'user':
                break;
        }

        return m;
    }

    ,removeUserGroup: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_');id = id[1];

        MODx.msg.confirm({
            title: _('warning')
            ,text: _('user_group_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/group/remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeUser: function(item,e) {
        var n = this.cm.activeNode;
        var user_id = n.id.substr(2).split('_');user_id = user_id[1];
        var group_id = n.parentNode.id.substr(2).split('_');group_id = group_id[1];

        MODx.msg.confirm({
            title: _('warning')
            ,text: _('user_group_user_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/group/removeUser'
                ,user_id: user_id
                ,group_id: group_id
            }
            ,listeners: {
                'success':{fn:this.refresh,scope:this}
            }
        });
    }

    ,_handleDrop: function(e) {
        s = false;
        switch (e.dropNode.attributes.type) {
            case 'user':
                s = !(e.point == 'above' || e.point == 'below');
                s = s && e.target.attributes.type == 'usergroup' && e.point == 'append';
            break;
            case 'usergroup':
                s = true;
            break;
        }
        return s;

    }
});
Ext.reg('modx-tree-usergroup',MODx.tree.UserGroup);

MODx.window.CreateUserGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'cugrp'+Ext.id();
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
            ,allowBlank: false
            ,anchor: '100%'
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
