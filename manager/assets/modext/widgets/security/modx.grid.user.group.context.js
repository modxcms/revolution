/**
 * @class MODx.grid.UserGroupContext
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-contexts
 */
MODx.grid.UserGroupContext = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template('<p class="desc">{permissions}</p>'),
        lazyRender: false,
        enableCaching: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-contexts'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/UserGroup/Context/GetList'
            ,usergroup: config.usergroup
            ,context: MODx.request.context || null
            ,policy: MODx.request.policy || null
        }
        ,fields: [
            'id',
            'target',
            'principal',
            'authority',
            'authority_name',
            'policy',
            'policy_name',
            'permissions',
            'cls'
        ]
        ,paging: true
        ,hideMode: 'offsets'
        ,grouping: true
        ,groupBy: 'authority_name'
        ,singleText: _('policy')
        ,pluralText: _('policies')
        ,sortBy: 'authority'
        ,sortDir: 'ASC'
        ,remoteSort: true
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('context')
            ,dataIndex: 'target'
            ,width: 120
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=context/update&key=' + record.data.target
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 100
            ,sortable: false
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
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/access/policy/update&id=' + record.data.policy
                    ,target: '_blank'
                });
            }, scope: this }
        }]
        ,tbar: [{
            text: _('context_add')
            ,cls:'primary-button'
            ,scope: this
            ,handler: this.createAcl
        },'->',{
            xtype: 'modx-combo-context'
            ,itemId: 'filter-context'
            ,emptyText: _('filter_by_context')
            ,allowBlank: true
            ,width: 160
            ,value: MODx.request.context || null
            ,listeners: {
                select: {
                    fn: function (cmp, record, selectedIndex) {
                        this.applyGridFilter(cmp, 'context');
                    },
                    scope: this
                }
            }
        },{
            xtype: 'modx-combo-policy'
            ,itemId: 'filter-policy'
            ,emptyText: _('filter_by_policy')
            ,allowBlank: true
            ,width: 160
            ,value: MODx.request.policy || null
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'Administrator'
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
                        this.clearGridFilters('filter-context, filter-policy');
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
    MODx.grid.UserGroupContext.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');
};
Ext.extend(MODx.grid.UserGroupContext,MODx.grid.Grid,{
    combos: {}
    ,windows: {}

    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {

        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('access_context_update')
                    ,handler: this.updateAcl
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) { m.push('-'); }
                m.push({
                    text: _('access_context_remove')
                    ,handler: this.remove.createDelegate(this,["confirm_remove","Security/Access/UserGroup/Context/Remove"])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createAcl: function(itm,e) {
        var r = {
            principal: this.config.usergroup
        };
        if (!this.windows.createAcl) {
            this.windows.createAcl = MODx.load({
                xtype: 'modx-window-user-group-context-create'
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
                xtype: 'modx-window-user-group-context-update'
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
Ext.reg('modx-grid-user-group-context',MODx.grid.UserGroupContext);

/**
 * @class MODx.window.CreateUGAccessContext
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-context-create
 */
MODx.window.CreateUGAccessContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'cugactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,url: MODx.config.connector_url
        ,action: 'Security/Access/UserGroup/Context/Create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,description: MODx.expandHelp ? '' : _('user_group_context_context_desc')
            ,id: 'modx-'+this.ident+'-context'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,allowBlank: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-context'
            ,html: _('user_group_context_context_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_context_authority_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_context_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_context_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'Administrator,Object'
                ,combo: true
            }
            ,allowBlank: false
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_context_policy_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'hidden'
            ,name: 'principal'
            ,hiddenName: 'principal'
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
                ,grow: false
                ,anchor: '100%'
                ,height: 150
                ,width: '97%'
                ,readOnly: true
            }]
        }]
    });
    MODx.window.CreateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGAccessContext,MODx.Window,{
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
Ext.reg('modx-window-user-group-context-create',MODx.window.CreateUGAccessContext);

/**
 * @class MODx.window.UpdateUGAccessContext
 * @extends MODx.window.CreateUGAccessContext
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-context-update
 */
MODx.window.UpdateUGAccessContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'uugactx'+Ext.id();
    Ext.applyIf(config,{
        title: _('ugc_mutate')
        ,action: 'Security/Access/UserGroup/Context/Update'
    });
    MODx.window.UpdateUGAccessContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGAccessContext,MODx.window.CreateUGAccessContext);
Ext.reg('modx-window-user-group-context-update',MODx.window.UpdateUGAccessContext);
