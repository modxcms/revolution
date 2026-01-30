/**
 * @class MODx.grid.UserGroupCategory
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-categories
 */
MODx.grid.UserGroupCategory = function UserGroupCategory(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-category', dependentParams: ['category'] },
        { filterId: 'filter-category', dependentParams: ['policy'] }
    ];
    this.aclType = 'category';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-categories',
        baseParams: {
            action: 'Security/Access/UserGroup/Category/GetList',
            usergroup: config.usergroup,
            category: MODx.request.category || null,
            policy: this.applyRequestFilter(2),
            isGroupingGrid: true
        },
        fields: [
            'id',
            'target',
            'name',
            'principal',
            'authority',
            'role_display',
            'policy',
            'policy_name',
            'context_key',
            'permissions',
            'cls'
        ],
        columns: this.getColumns([
            {
                header: _('category'),
                dataIndex: 'name',
                width: 120,
                sortable: true
            }, {
                header: _('minimum_role'),
                dataIndex: 'role_display',
                width: 100,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('security/permission', 'role_display')
            }, {
                header: _('policy'),
                dataIndex: 'policy_name',
                width: 200,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('security/access/policy/update', 'policy_name', {
                    linkParams: [{ key: 'id', valueIndex: 'policy' }]
                })
            }, {
                header: _('context'),
                dataIndex: 'context_key',
                width: 150,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('context/update', 'context_key', {
                    linkParams: [{ key: 'key', valueIndex: 'context_key' }]
                })
            }
        ]),
        tbar: [
            {
                text: _('category_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-category',
                itemId: 'filter-category',
                emptyText: _('filter_by_category'),
                width: 200,
                allowBlank: true,
                displayField: 'category',
                value: MODx.request.category || null,
                baseParams: {
                    action: 'Element/Category/GetList',
                    isGridFilter: true,
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-category', 'category', record.data.id);
                            this.applyGridFilter(cmp, 'category');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-category',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(2),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Element,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupCategory',
                    usergroup: config.usergroup
                },
                listeners: {
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
    MODx.grid.UserGroupCategory.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupCategory, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-category', MODx.grid.UserGroupCategory);

/**
 * @class MODx.window.CreateUGCat
 * @extends MODx.window.UserGroupAclBase
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-category-create
 */
MODx.window.CreateUGCat = function CreateUGCat(config = {}) {
    this.aclType = 'category';
    this.windowId = config.ident || `${this.aclType}-access-create-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    const categoryDesc = _('user_group_category_category_desc');
    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/Category/Create',
        fields: this.getWindowFields([
            {
                xtype: 'modx-combo-category',
                fieldLabel: _('category'),
                description: MODx.expandHelp ? '' : categoryDesc,
                id: `${this.idPrefix}-category`,
                name: 'target',
                hiddenName: 'target',
                editable: false,
                anchor: '100%'
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: categoryDesc,
                cls: 'desc-under'
            }
        ])
    });
    MODx.window.CreateUGCat.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateUGCat, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-category-create', MODx.window.CreateUGCat);

/**
 * @class MODx.window.UpdateUGCat
 * @extends MODx.window.CreateUGCat
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-category-update
 */
MODx.window.UpdateUGCat = function UpdateUGCat(config = {}) {
    this.aclType = 'category';
    this.windowId = config.ident || `${this.aclType}-access-update-${Ext.id()}`;
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/Category/Update'
    });
    MODx.window.UpdateUGCat.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGCat, MODx.window.CreateUGCat);
Ext.reg('modx-window-user-group-category-update', MODx.window.UpdateUGCat);
