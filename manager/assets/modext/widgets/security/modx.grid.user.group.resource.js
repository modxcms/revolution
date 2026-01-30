/**
 * @class MODx.grid.UserGroupResourceGroup
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-resource-groups
 */
MODx.grid.UserGroupResourceGroup = function UserGroupResourceGroup(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-resourceGroup', dependentParams: ['resourceGroup'] },
        { filterId: 'filter-resourceGroup', dependentParams: ['policy'] }
    ];
    this.aclType = 'resourcegroup';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-resource-groups',
        baseParams: {
            action: 'Security/Access/UserGroup/ResourceGroup/GetList',
            usergroup: config.usergroup,
            resourceGroup: MODx.request.resourceGroup || null,
            policy: this.applyRequestFilter(1),
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
                header: _('resource_group'),
                dataIndex: 'name',
                width: 120,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('security/resourcegroup', 'name')
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
                text: _('resource_group_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-resourcegroup',
                itemId: 'filter-resourceGroup',
                emptyText: _('filter_by_resource_group'),
                width: 210,
                allowBlank: true,
                value: MODx.request.resourceGroup || null,
                baseParams: {
                    action: 'Security/ResourceGroup/GetList',
                    isGridFilter: true,
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-resourceGroup', 'resourceGroup', record.data.id);
                            this.applyGridFilter(cmp, 'resourceGroup');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-resourceGroup',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(1),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Resource,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupResourceGroup',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-resourceGroup', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-resourceGroup, filter-policy-resourceGroup',
                'filter-policy-resourceGroup:resourceGroup, filter-resourceGroup:policy'
            )
        ]
    });
    MODx.grid.UserGroupResourceGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupResourceGroup, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-resource-group', MODx.grid.UserGroupResourceGroup);

/**
 * @class MODx.window.CreateUGRG
 * @extends MODx.window.UserGroupAclBase
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-resourcegroup-create
 */
MODx.window.CreateUGRG = function CreateUGRG(config = {}) {
    this.aclType = 'resourcegroup';
    this.windowId = config.ident || `${this.aclType}-access-create-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    const resourceGroupDesc = _('user_group_resourcegroup_resource_group_desc');

    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/ResourceGroup/Create',
        fields: this.getWindowFields([
            {
                xtype: 'modx-combo-resourcegroup',
                fieldLabel: _('resource_group'),
                description: MODx.expandHelp ? '' : resourceGroupDesc,
                id: `${this.idPrefix}-resource-group`,
                name: 'target',
                hiddenName: 'target',
                editable: false,
                anchor: '100%'
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: resourceGroupDesc,
                cls: 'desc-under'
            }
        ])
    });
    MODx.window.CreateUGRG.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateUGRG, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-resourcegroup-create', MODx.window.CreateUGRG);

/**
 * @class MODx.window.UpdateUGRG
 * @extends MODx.window.CreateUGRG
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-resourcegroup-update
 */
MODx.window.UpdateUGRG = function UpdateUGRG(config = {}) {
    this.aclType = 'resourcegroup';
    this.windowId = config.ident || `${this.aclType}-access-update-${Ext.id()}`;
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/ResourceGroup/Update'
    });
    MODx.window.UpdateUGRG.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGRG, MODx.window.CreateUGRG);
Ext.reg('modx-window-user-group-resourcegroup-update', MODx.window.UpdateUGRG);
