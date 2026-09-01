/**
 * @class MODx.grid.UserGroupContextGroup
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-context-group
 */
MODx.grid.UserGroupContextGroup = function UserGroupContextGroup(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-contextGroup', dependentParams: ['contextGroup'] },
        { filterId: 'filter-contextGroup', dependentParams: ['policy'] }
    ];
    this.aclType = 'contextgroup';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-context-groups',
        baseParams: {
            action: 'Security/Access/UserGroup/ContextGroup/GetList',
            usergroup: config.usergroup,
            contextGroup: MODx.request.contextGroup || null,
            policy: this.applyRequestFilter(0),
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
            'policyPermissions'
        ],
        sortBy: 'name',
        columns: this.getColumns([
            {
                header: _('context_groups'),
                dataIndex: 'name',
                sortable: true
            }, {
                header: _('minimum_role'),
                dataIndex: 'role_display',
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('security/permission', 'role_display')
            }, {
                header: _('policy'),
                dataIndex: 'policy_name',
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('security/access/policy/update', 'policy_name', {
                    linkParams: [{ key: 'id', valueIndex: 'policy' }]
                })
            }
        ]),
        tbar: [
            {
                text: _('context_group_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-context-group',
                itemId: 'filter-contextGroup',
                emptyText: _('context_group_filter'),
                width: 210,
                allowBlank: true,
                showNone: false,
                value: MODx.request.contextGroup || null,
                baseParams: {
                    action: 'Context/Group/GetList',
                    combo: true,
                    showNone: false,
                    isGridFilter: true,
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record) {
                            this.updateDependentFilter('filter-policy-contextGroup', 'contextGroup', record.data.id);
                            this.applyGridFilter(cmp, 'contextGroup');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-contextGroup',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(0),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Administrator,Context,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupContextGroup',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record) {
                            this.updateDependentFilter('filter-contextGroup', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-contextGroup, filter-policy-contextGroup',
                'filter-policy-contextGroup:contextGroup, filter-contextGroup:policy'
            )
        ]
    });
    MODx.grid.UserGroupContextGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupContextGroup, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-context-group', MODx.grid.UserGroupContextGroup);

/**
 * @class MODx.window.CreateUGAccessContextGroup
 * @extends MODx.window.UserGroupAclBase
 * @xtype modx-window-user-group-contextgroup-create
 */
MODx.window.CreateUGAccessContextGroup = function CreateUGAccessContextGroup(config = {}) {
    this.aclType = 'contextgroup';
    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/ContextGroup/Create'
    });
    this.windowId = config.ident || `${this.aclType}-access-${config.saveMode}-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    const contextGroupDesc = _('user_group_contextgroup_context_group_desc');
    Ext.applyIf(config, {
        fields: this.getWindowFields([
            {
                xtype: 'modx-combo-context-group',
                fieldLabel: _('context_group'),
                description: MODx.expandHelp ? '' : contextGroupDesc,
                id: `${this.idPrefix}-context-group`,
                name: 'target',
                hiddenName: 'target',
                editable: false,
                allowBlank: false,
                showNone: false,
                anchor: '100%',
                baseParams: {
                    action: 'Context/Group/GetList',
                    combo: true,
                    showNone: false
                }
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: contextGroupDesc,
                cls: 'desc-under'
            }
        ])
    });
    MODx.window.CreateUGAccessContextGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateUGAccessContextGroup, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-contextgroup-create', MODx.window.CreateUGAccessContextGroup);

/**
 * @class MODx.window.UpdateUGAccessContextGroup
 * @extends MODx.window.CreateUGAccessContextGroup
 * @xtype modx-window-user-group-contextgroup-update
 */
MODx.window.UpdateUGAccessContextGroup = function UpdateUGAccessContextGroup(config = {}) {
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/ContextGroup/Update'
    });
    MODx.window.UpdateUGAccessContextGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGAccessContextGroup, MODx.window.CreateUGAccessContextGroup);
Ext.reg('modx-window-user-group-contextgroup-update', MODx.window.UpdateUGAccessContextGroup);
