/**
 * @class MODx.grid.UserGroupSource
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-sources
 */
MODx.grid.UserGroupSource = function UserGroupSource(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-source', dependentParams: ['source'] },
        { filterId: 'filter-source', dependentParams: ['policy'] }
    ];
    this.aclType = 'source';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-sources',
        baseParams: {
            action: 'Security/Access/UserGroup/Source/GetList',
            usergroup: config.usergroup,
            source: MODx.request.source || null,
            policy: this.applyRequestFilter(3),
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
                header: _('source'),
                dataIndex: 'name',
                width: 120,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('source/update', 'name', {
                    linkParams: [{ key: 'id', valueIndex: 'target' }]
                })
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
                text: _('source_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-source',
                itemId: 'filter-source',
                emptyText: _('filter_by_source'),
                width: 200,
                allowBlank: true,
                value: MODx.request.source || null,
                baseParams: {
                    action: 'Source/GetList',
                    isGridFilter: true,
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-source', 'source', record.data.id);
                            this.applyGridFilter(cmp, 'source');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-source',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(3),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'MediaSource',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupSource',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-source', 'policy', record.data.id);
                            this.applyGridFilter(cmp, 'policy');
                        },
                        scope: this
                    }
                }
            },
            this.getClearFiltersButton(
                'filter-source, filter-policy-source',
                'filter-policy-source:source, filter-source:policy'
            )
        ]
    });
    MODx.grid.UserGroupSource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupSource, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-source', MODx.grid.UserGroupSource);

/**
 * @class MODx.window.CreateUGSource
 * @extends MODx.window.UserGroupAclBase
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-source-create
 */
MODx.window.CreateUGSource = function CreateUGSource(config = {}) {
    this.aclType = 'source';
    this.windowId = config.ident || `${this.aclType}-access-create-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    const sourceDesc = _('user_group_source_source_desc');
    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/Source/Create',
        fields: this.getWindowFields([
            {
                xtype: 'modx-combo-source',
                fieldLabel: _('source'),
                description: MODx.expandHelp ? '' : sourceDesc,
                id: `${this.idPrefix}-source`,
                name: 'target',
                hiddenName: 'target',
                editable: false,
                anchor: '100%'
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: sourceDesc,
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
    MODx.window.CreateUGSource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateUGSource, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-source-create', MODx.window.CreateUGSource);

/**
 * @class MODx.window.UpdateUGSource
 * @extends MODx.window.CreateUGSource
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-source-update
 */
MODx.window.UpdateUGSource = function UpdateUGSource(config = {}) {
    this.aclType = 'source';
    this.windowId = config.ident || `${this.aclType}-access-update-${Ext.id()}`;
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/Source/Update'
    });
    MODx.window.UpdateUGSource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGSource, MODx.window.CreateUGSource);
Ext.reg('modx-window-user-group-source-update', MODx.window.UpdateUGSource);
