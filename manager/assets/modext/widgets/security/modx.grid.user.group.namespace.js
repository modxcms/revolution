/**
 * @class MODx.grid.UserGroupNamespace
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-namespace
 */
MODx.grid.UserGroupNamespace = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template('<p class="desc">{permissions}</p>'),
        lazyRender: false,
        enableCaching: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-namespace'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/UserGroup/AccessNamespace/GetList'
            ,usergroup: config.usergroup
            ,namespace: MODx.request.namespace || null
            ,policy: MODx.request.policy || null
        }
        ,paging: true
        ,hideMode: 'offsets'
        ,fields: [
            'id',
            'target',
            'name',
            'principal',
            'authority',
            'authority_name',
            'policy',
            'context_key',
            'policy_name',
            'permissions',
            'menu'
        ]
        ,grouping: true
        ,groupBy: 'authority_name'
        ,singleText: _('policy')
        ,pluralText: _('policies')
        ,sortBy: 'authority'
        ,sortDir: 'ASC'
        ,remoteSort: true
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('namespace')
            ,dataIndex: 'name'
            ,width: 120
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=workspaces/namespace'
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 100
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/permission'
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 200
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/access/policy/update&id=' + record.data.policy
                    ,target: '_blank'
                });
            }, scope: this }
        }]
        ,tbar: [{
            text: _('namespace_add')
            ,cls:'primary-button'
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-namespace'
            ,itemId: 'filter-namespace'
            ,emptyText: _('filter_by_namespace')
            ,width: 200
            ,allowBlank: true
            ,value: MODx.request.namespace || null
            ,listeners: {
                select: {
                    fn: function (cmp, record, selectedIndex) {
                        this.applyGridFilter(cmp, 'namespace');
                    },
                    scope: this
                }
            }
        },{
            xtype: 'modx-combo-policy'
            ,itemId: 'filter-policy'
            ,emptyText: _('filter_by_policy')
            ,allowBlank: true
            ,value: MODx.request.policy || null
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'Namespace'
            }
            ,listeners: {
                select: {
                    fn: function (cmp, record, selectedIndex) {
                        this.applyGridFilter(cmp, 'policy');
                    },
                    scope: this
                }
            }
        },{
            text: _('filter_clear')
            ,itemId: 'filter-clear'
            ,listeners: {
                click: {
                    fn: function() {
                        this.clearGridFilters('filter-namespace, filter-policy');
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
    MODx.grid.UserGroupNamespace.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupNamespace,MODx.grid.Grid,{
    combos: {}
    ,windows: {}

    ,createAcl: function(itm,e) {
        var r = {
            principal: this.config.usergroup
        };
        if (!this.windows.createAcl) {
            this.windows.createAcl = MODx.load({
                xtype: 'modx-window-user-group-namespace-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('createAcl',r);
                    },scope:this}
                }
            });
        }
        this.windows.createAcl.setValues(r);
        this.windows.createAcl.show(e.target);
    }

    ,updateAcl: function(itm,e) {
        var r = this.menu.record;

        if (!this.windows.updateAcl) {
            this.windows.updateAcl = MODx.load({
                xtype: 'modx-window-user-group-namespace-update'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                        this.fireEvent('updateAcl',r);
                    },scope:this}
                }
            });
        }
        this.windows.updateAcl.setValues(r);
        this.windows.updateAcl.show(e.target);
    }
});
Ext.reg('modx-grid-user-group-namespace',MODx.grid.UserGroupNamespace);

/**
 * @class MODx.window.CreateUGNamespace
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-namespace-create
 */
MODx.window.CreateUGNamespace = function(config) {
    config = config || {};
    this.ident = config.ident || 'cugnamespace'+Ext.id();
    Ext.applyIf(config,{
        title: _('namespace_add')
        ,url: MODx.config.connector_url
        ,action: 'Security/Access/UserGroup/AccessNamespace/Create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,value: 'MODX\\Revolution\\modUserGroup'
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,value: 'mgr'
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,description: MODx.expandHelp ? '' : _('user_group_source_source_desc')
            ,id: 'modx-'+this.ident+'-namespace'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-source'
            ,html: _('user_group_source_source_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_source_authority_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_source_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_source_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'Namespace'
            }
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_source_policy_desc')
            ,cls: 'desc-under'
        },{
            id: 'modx-'+this.ident+'-permissions-list-ct'
            ,cls: 'modx-permissions-list'
            ,defaults: {border: false}
            ,autoHeight: true
            ,hidden: true
            ,anchor: '100%'
            ,items: [{
                html: '<h4>'+_('permissions_in_policy')+'</h4>'
                ,id: 'modx-'+this.ident+'-permissions-list-header'
            },{
                id: 'modx-'+this.ident+'-permissions-list'
                ,cls: 'modx-permissions-list-textarea'
                ,xtype: 'textarea'
                ,name: 'permissions'
                ,grow: false
                ,anchor: '100%'
                ,height: 100
                ,width: '97%'
                ,readOnly: true
            }]
        }]
    });
    MODx.window.CreateUGNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGNamespace,MODx.Window,{
    onPolicySelect: function(cb,rec,idx) {
        var s = cb.getStore();
        if (!s) return;

        var r = s.getAt(idx);
        var lc = Ext.getCmp('modx-'+this.ident+'-permissions-list-ct');
        if (r && idx>0) {
            lc.show();
            var pl = Ext.getCmp('modx-'+this.ident+'-permissions-list');
            var o = rec.data.permissions.join(', ');
            pl.setValue(o);
        } else {
            lc.hide();
        }
        this.doLayout();
    }
});
Ext.reg('modx-window-user-group-namespace-create',MODx.window.CreateUGNamespace);

/**
 * @class MODx.window.UpdateUGNamespace
 * @extends MODx.window.CreateUGNamespace
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-namespace-update
 */
MODx.window.UpdateUGNamespace = function(config) {
    config = config || {};
    this.ident = config.ident || 'updugsrc'+Ext.id();
    Ext.applyIf(config,{
        title: _('access_namespace_update')
        ,action: 'Security/Access/UserGroup/AccessNamespace/Update'
    });
    MODx.window.UpdateUGNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGNamespace,MODx.window.CreateUGNamespace);
Ext.reg('modx-window-user-group-namespace-update',MODx.window.UpdateUGNamespace);
