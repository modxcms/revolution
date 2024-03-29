/**
 * @class MODx.grid.UserGroupCategory
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-categories
 */
MODx.grid.UserGroupCategory = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template('<p class="desc">{permissions}</p>'),
        lazyRender: false,
        enableCaching: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-user-group-categories'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Access/UserGroup/Category/GetList'
            ,usergroup: config.usergroup
            ,category: MODx.request.category || null
            ,policy: this.applyRequestFilter(2)
        }
        ,fields: [
            'id',
            'target',
            'name',
            'principal',
            'authority',
            'authority_name',
            'policy',
            'policy_name',
            'context_key',
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
                header: _('category')
                ,dataIndex: 'name'
                ,width: 120
                ,sortable: true
            },{
                header: _('minimum_role')
                ,dataIndex: 'authority_name'
                ,width: 100
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('security/permission', 'authority_name')
            },{
                header: _('policy')
                ,dataIndex: 'policy_name'
                ,width: 200
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('security/access/policy/update', 'policy_name', {
                    linkParams: [{ key: 'id', valueIndex: 'policy'}]
                })
            },{
                header: _('context')
                ,dataIndex: 'context_key'
                ,width: 150
                ,sortable: true
                ,xtype: 'templatecolumn'
                ,tpl: this.getLinkTemplate('context/update', 'context_key', {
                    linkParams: [{ key: 'key', valueIndex: 'context_key'}]
                })
            }
        ]
        ,tbar: [
            {
                text: _('category_add')
                ,cls: 'primary-button'
                ,scope: this
                ,handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-category'
                ,itemId: 'filter-category'
                ,emptyText: _('filter_by_category')
                ,width: 200
                ,allowBlank: true
                ,displayField: 'category'
                ,value: MODx.request.category || null
                ,baseParams: {
                    action: 'Element/Category/GetList',
                    isGridFilter: true,
                    usergroup: config.usergroup
                }
                ,listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-category', 'category', record.data.id);
                            this.applyGridFilter(cmp, 'category');
                        },
                        scope: this
                    }
                }
            },{
                xtype: 'modx-combo-policy'
                ,itemId: 'filter-policy-category'
                ,emptyText: _('filter_by_policy')
                ,width: 180
                ,allowBlank: true
                ,value: this.applyRequestFilter(2)
                ,baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Element,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupCategory',
                    usergroup: config.usergroup
                }
                ,listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-category', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-category, filter-policy-category',
                'filter-policy-category:category, filter-category:policy'
            )
        ]
    });
    MODx.grid.UserGroupCategory.superclass.constructor.call(this,config);
    this.addEvents('createAcl','updateAcl');

    const gridFilterData = [
        { filterId: 'filter-policy-category', dependentParams: ['category'] },
        { filterId: 'filter-category', dependentParams: ['policy'] }
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
};
Ext.extend(MODx.grid.UserGroupCategory,MODx.grid.Grid,{
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
                    text: _('access_category_update'),
                    handler: this.updateAcl
                });
            }
            if (permissions.indexOf('premove') != -1) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('access_category_remove'),
                    handler: this.remove.createDelegate(this,['confirm_remove','Security/Access/UserGroup/Category/Remove'])
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
                xtype: 'modx-window-user-group-category-create'
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
                xtype: 'modx-window-user-group-category-update'
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
Ext.reg('modx-grid-user-group-category',MODx.grid.UserGroupCategory);

/**
 * @class MODx.window.CreateUGCat
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-category-create
 */
MODx.window.CreateUGCat = function(config = {}) {
    this.ident = config.ident || 'cugcat'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_add')
        ,url: MODx.config.connector_url
        ,action: 'Security/Access/UserGroup/Category/Create'
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
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,description: MODx.expandHelp ? '' : _('user_group_category_category_desc')
            ,id: 'modx-'+this.ident+'-category'
            ,name: 'target'
            ,hiddenName: 'target'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-category'
            ,html: _('user_group_category_category_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-context'
            ,fieldLabel: _('context')
            ,description: MODx.expandHelp ? '' : _('user_group_category_context_desc')
            ,id: 'modx-'+this.ident+'-context'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,editable: false
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-context'
            ,html: _('user_group_category_context_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,description: MODx.expandHelp ? '' : _('user_group_category_policy_desc')
            ,id: 'modx-'+this.ident+'-authority'
            ,name: 'authority'
            ,value: 0
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-authority'
            ,html: _('user_group_category_authority_desc')
            ,cls: 'desc-under'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,description: MODx.expandHelp ? '' : _('user_group_category_policy_desc')
            ,id: 'modx-'+this.ident+'-policy'
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'Element,Object'
                ,combo: true
            }
            ,anchor: '100%'
            ,listeners: {
                'select':{fn:this.onPolicySelect,scope:this}
            }
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-policy'
            ,html: _('user_group_category_policy_desc')
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
    MODx.window.CreateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUGCat,MODx.Window,{
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
Ext.reg('modx-window-user-group-category-create',MODx.window.CreateUGCat);

/**
 * @class MODx.window.UpdateUGCat
 * @extends MODx.window.CreateUGCat
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-category-update
 */
MODx.window.UpdateUGCat = function(config = {}) {
    this.ident = config.ident || 'updugcat'+Ext.id();
    Ext.applyIf(config,{
        title: _('access_category_update')
        ,action: 'Security/Access/UserGroup/Category/Update'
    });
    MODx.window.UpdateUGCat.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateUGCat,MODx.window.CreateUGCat);
Ext.reg('modx-window-user-group-category-update',MODx.window.UpdateUGCat);
