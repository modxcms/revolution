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
            ,policy: this.applyRequestFilter(0)
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
        ,columns: [
            this.exp,
            {
                header: _('context')
                ,dataIndex: 'target'
                ,sortable: true
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('context/update', 'target', {
                    linkParams: [{ key: 'key', valueIndex: 'target'}]
                })
            },{
                header: _('minimum_role')
                ,dataIndex: 'authority_name'
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('security/permission', 'authority_name')
            },{
                header: _('policy')
                ,dataIndex: 'policy_name'
                ,sortable: true
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('security/access/policy/update', 'policy_name', {
                    linkParams: [{ key: 'id', valueIndex: 'policy'}]
                })
            }
        ]
        ,tbar: [
            {
                text: _('context_add')
                ,cls: 'primary-button'
                ,scope: this
                ,handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-context'
                ,itemId: 'filter-context'
                ,emptyText: _('filter_by_context')
                ,width: 180
                ,allowBlank: true
                ,value: MODx.request.context || null
                ,baseParams: {
                    action: 'Context/GetList',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupContext',
                    usergroup: config.usergroup
                }
                ,listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-context', 'context', record.data.key);
                            this.applyGridFilter(cmp, 'context');
                        },
                        scope: this
                    }
                }
            },{
                xtype: 'modx-combo-policy'
                ,itemId: 'filter-policy-context'
                ,emptyText: _('filter_by_policy')
                ,width: 180
                ,allowBlank: true
                ,value: this.applyRequestFilter(0)
                ,baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Administrator,Context,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupContext',
                    usergroup: config.usergroup
                }
                ,listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-context', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-context, filter-policy-context',
                'filter-policy-context:context, filter-context:policy'
            )
        ]
    });
    MODx.grid.UserGroupContext.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');

    const gridFilterData = [
        { filterId: 'filter-policy-context', dependentParams: ['context'] },
        { filterId: 'filter-context', dependentParams: ['policy'] }
    ];

    this.on({
        createAcl: function() {
            if (arguments[0].a.response.status == 200) {
                this.refreshFilterOptions(gridFilterData);
            }
        },
        updateAcl: function() {
            if (arguments[0].a.response.status == 200) {
                this.refreshFilterOptions(gridFilterData);
            }
        },
        afterRemoveRow: function() {
            this.refreshFilterOptions(gridFilterData);
        }
    });
}
Ext.extend(MODx.grid.UserGroupContext,MODx.grid.Grid,{

    combos: {}
    ,windows: {}

    ,getMenu: function() {
        const record = this.getSelectionModel().getSelected(),
              permissions = record.data.cls,
              menu = []
        ;

        if (this.getSelectionModel().getCount() > 1) {
            // Currently not allowing bulk actions for this grid
        } else {
            if (permissions.indexOf('pedit') != -1) {
                menu.push({
                    text: _('access_context_update'),
                    handler: this.updateAcl
                });
            }
            if (permissions.indexOf('premove') != -1) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('access_context_remove'),
                    handler: this.remove.createDelegate(this,['confirm_remove','Security/Access/UserGroup/Context/Remove'])
                });
            }
        }

        if (menu.length > 0) {
            this.addContextMenuItem(menu);
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
                ,group: 'Administrator,Context,Object'
                ,combo: true
            }
            ,allowBlank: false
            ,anchor: '100%'
            ,listeners: {
                select: {
                    fn: this.onPolicySelect,
                    scope: this
                }
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
