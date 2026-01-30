/**
 * @class MODx.grid.UserGroupNamespace
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-namespace
 */
MODx.grid.UserGroupNamespace = function UserGroupNamespace(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-namespace', dependentParams: ['namespace'] },
        { filterId: 'filter-namespace', dependentParams: ['policy'] }
    ];
    this.aclType = 'namespace';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-namespace',
        baseParams: {
            action: 'Security/Access/UserGroup/AccessNamespace/GetList',
            usergroup: config.usergroup,
            namespace: this.applyRequestFilter(4, 'ns'),
            policy: this.applyRequestFilter(4),
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
                header: _('namespace'),
                dataIndex: 'name',
                width: 120,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('workspaces/namespace', 'name')
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
            }
        ]),
        tbar: [
            {
                text: _('namespace_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-namespace',
                itemId: 'filter-namespace',
                emptyText: _('filter_by_namespace'),
                editable: false,
                width: 200,
                allowBlank: true,
                value: this.applyRequestFilter(4, 'ns'),
                baseParams: {
                    action: 'Workspace/PackageNamespace/GetList',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupNamespace',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-namespace', 'namespace', record.data.name);
                            /*
                                There's an odd conflict in the processor when using 'namespace' as the
                                query param, therefor the alternate param 'ns' is used for this listener, its component value,
                                and in the value of this grid's main baseParams config
                            */
                            this.applyGridFilter(cmp, 'ns');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-namespace',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(4),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Namespace',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupNamespace',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-namespace', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-namespace, filter-policy-namespace',
                'filter-policy-namespace:namespace, filter-namespace:policy'
            )
        ]
    });
    MODx.grid.UserGroupNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupNamespace, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-namespace', MODx.grid.UserGroupNamespace);

/**
 * @class MODx.window.CreateUGNamespace
 * @extends MODx.window.UserGroupAclBase
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-namespace-create
 */
MODx.window.CreateUGNamespace = function CreateUGNamespace(config = {}) {
    this.aclType = 'namespace';
    this.windowId = config.ident || `${this.aclType}-access-create-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    const namespaceDesc = _('user_group_namespace_namespace_desc');
    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/AccessNamespace/Create',
        fields: this.getWindowFields([
            {
                xtype: 'modx-combo-namespace',
                fieldLabel: _('namespace'),
                description: MODx.expandHelp ? '' : namespaceDesc,
                id: `${this.idPrefix}-namespace`,
                name: 'target',
                hiddenName: 'target',
                editable: false,
                anchor: '100%'
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: namespaceDesc,
                cls: 'desc-under'
            }
        ], [
            {
                xtype: 'hidden',
                name: 'context_key',
                hiddenName: 'context_key',
                value: 'mgr'
            }
        ])
    });
    MODx.window.CreateUGNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateUGNamespace, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-namespace-create', MODx.window.CreateUGNamespace);

/**
 * @class MODx.window.UpdateUGNamespace
 * @extends MODx.window.CreateUGNamespace
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-namespace-update
 */
MODx.window.UpdateUGNamespace = function UpdateUGNamespace(config = {}) {
    this.aclType = 'namespace';
    this.windowId = config.ident || `${this.aclType}-access-update-${Ext.id()}`;
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/AccessNamespace/Update'
    });
    MODx.window.UpdateUGNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGNamespace, MODx.window.CreateUGNamespace);
Ext.reg('modx-window-user-group-namespace-update', MODx.window.UpdateUGNamespace);
