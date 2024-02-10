/**
 * Loads the Users panel
 *
 * @class MODx.panel.Users
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-users
 */
MODx.panel.Users = function(config = {}) {
    Ext.applyIf(config,{
        id: 'modx-panel-users'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('users')
            ,id: 'modx-users-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('users')
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('user_management_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-user'
                ,cls:'main-wrapper'
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Users,MODx.FormPanel);
Ext.reg('modx-panel-users',MODx.panel.Users);

/**
 * Loads a grid of User.
 *
 * @class MODx.grid.User
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user
 */
MODx.grid.User = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/User/GetList'
            ,usergroup: MODx.request.usergroup || null
        }
        ,fields: [
            'id',
            'username',
            'fullname',
            'email',
            'gender',
            'blocked',
            'role',
            'active',
            'cls'
        ]
        ,paging: true
        ,autosave: true
        ,save_action: 'Security/User/UpdateFromGrid'
        ,autosaveErrorMsg: _('user_err_save')
        ,remoteSort: true
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('username')
            ,dataIndex: 'username'
            ,width: 150
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/user/update&id=' + record.data.id
                });
            }, scope: this }
        },{
            header: _('user_full_name')
            ,dataIndex: 'fullname'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('email')
            ,dataIndex: 'email'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('active')
            ,dataIndex: 'active'
            ,width: 80
            ,sortable: true
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        },{
            header: _('user_block')
            ,dataIndex: 'blocked'
            ,width: 80
            ,sortable: true
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }]
        ,tbar: [
            {
                text: _('create')
                ,handler: this.createUser
                ,scope: this
                ,cls:'primary-button'
            },{
                text: _('bulk_actions')
                ,menu: [
                    {
                        text: _('selected_activate')
                        ,handler: this.activateSelected
                        ,scope: this
                    },{
                        text: _('selected_deactivate')
                        ,handler: this.deactivateSelected
                        ,scope: this
                    },{
                        text: _('selected_remove')
                        ,handler: this.removeSelected
                        ,scope: this
                    }
                ]
            },
            '->',
            {
                xtype: 'modx-combo-usergroup'
                ,itemId: 'filter-usergroup'
                ,emptyText: `${_('user_group')}...`
                ,baseParams: {
                    action: 'Security/Group/GetList'
                    ,addAll: true
                }
                ,value: MODx.request.usergroup || null
                ,width: 200
                ,listeners: {
                    select: {
                        fn: function (cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'usergroup');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(),
            this.getClearFiltersButton('filter-usergroup, filter-query')
        ]
    });
    MODx.grid.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.User,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            });
            m.push({
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            });
            m.push('-');
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            });
        } else {
            if (p.indexOf('pupdate') != -1) {
                m.push({
                    text: _('edit')
                    ,handler: this.updateUser
                });
            }
            if (p.indexOf('pcopy') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('duplicate')
                    ,handler: this.duplicateUser
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('delete')
                    ,handler: this.removeUser
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createUser: function() {
        MODx.loadPage('security/user/create');
    }

    ,updateUser: function() {
        MODx.loadPage('security/user/update', 'id='+this.menu.record.id);
    }

    ,duplicateUser: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/User/Duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeUser: function() {
        MODx.msg.confirm({
            title: _('delete')
            ,text: _('user_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'Security/User/Delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/User/ActivateMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/User/DeactivateMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('selected_remove')
            ,text: _('user_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Security/User/RemoveMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }

    ,rendGender: function(d,c) {
        switch(d.toString()) {
            case '0':
                return '-';
            case '1':
                return _('male');
            case '2':
                return _('female');
        }
    }
});
Ext.reg('modx-grid-user',MODx.grid.User);
