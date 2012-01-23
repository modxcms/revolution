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
        ,url: MODx.config.connectors_url+'security/group.php'
        ,root_id: 'n_ug_0'
        ,root_name: _('user_groups')
        ,enableDrag: true
        ,enableDrop: true
        ,rootVisible: true
        ,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,tbar: [{
            text: _('user_group_new')
            ,scope: this
            ,handler: this.createUserGroup.createDelegate(this,[true],true)
        }]
    });
    MODx.tree.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.UserGroup,MODx.tree.Tree,{	
    windows: {}
	
    ,addUser: function(item,e) {
        var n = this.cm.activeNode;
        var ug = n.id.substr(2).split('_');ug = ug[1];
        if (ug === undefined) {ug = 0;}
        var r = {usergroup: ug};

        if (!this.windows.adduser) {
            this.windows.adduser = MODx.load({
                xtype: 'modx-window-usergroup-adduser'
                ,record: r
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
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
                ,record: r
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.windows.createUsergroup.setValues(r);
        }
        this.windows.createUsergroup.show(e.target);
    }
    
    ,updateUserGroup: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_');id = id[1];
        
        location.href = 'index.php'
            + '?a=' + MODx.action['security/usergroup/update']
            + '&id=' + id;
    }

    ,getMenu: function() {
        var m = [];
        var n = this.cm.activeNode;
        var ui = n.getUI();

        switch (n.attributes.type) {
            case 'usergroup':
                if (ui.hasClass('padduser')) {
                    m.push({
                        text: _('user_group_user_add')
                        ,handler: this.addUser
                    });
                    m.push('-');
                }
                if (ui.hasClass('pcreate')) {
                    m.push({
                        text: _('user_group_create')
                        ,handler: this.createUserGroup
                    });
                }
                if (ui.hasClass('pupdate')) {
                    m.push({
                        text: _('user_group_update')
                        ,handler: this.updateUserGroup
                    });
                }
                if (ui.hasClass('premove')) {
                    m.push('-');
                    m.push({
                        text: _('user_group_remove')
                        ,handler: this.removeUserGroup
                    });
                }
                break;
            case 'user':
                m.push({
                    text: _('user_group_user_remove')
                    ,handler: this.removeUser
                })
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
                action: 'remove'
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
                action: 'removeUser'
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
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/group.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('user_group_desc_name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
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
            name: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'hidden'
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
        ,height: 150
        ,width: 375
        ,url: MODx.config.connectors_url+'security/usergroup/user.php'
        ,action: 'create'
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
