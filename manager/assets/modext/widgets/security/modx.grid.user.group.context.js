/**
 * @class MODx.grid.UserGroupContext
 * @extends MODx.grid.UserGroupBase
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-contexts
 */
MODx.grid.UserGroupContext = function UserGroupContext(config = {}) {
    this.gridFilterData = [
        { filterId: 'filter-policy-context', dependentParams: ['context'] },
        { filterId: 'filter-context', dependentParams: ['policy'] }
    ];
    this.aclType = 'context';
    Ext.applyIf(config, {
        id: 'modx-grid-user-group-contexts',
        baseParams: {
            action: 'Security/Access/UserGroup/Context/GetList',
            usergroup: config.usergroup,
            context: MODx.request.context || null,
            policy: this.applyRequestFilter(0),
            isGroupingGrid: true
        },
        fields: [
            'id',
            'target',
            'principal',
            'authority',
            'role_display',
            'policy',
            'policy_name',
            'permissions',
            'cls'
        ],
        sortBy: 'target',
        columns: this.getColumns([
            {
                header: _('context'),
                dataIndex: 'target',
                width: 120,
                sortable: true,
                xtype: 'templatecolumn',
                tpl: this.getLinkTemplate('context/update', 'target', {
                    linkParams: [{ key: 'key', valueIndex: 'target' }]
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
                text: _('context_add'),
                cls: 'primary-button',
                scope: this,
                handler: this.createAcl
            },
            '->',
            {
                xtype: 'modx-combo-context',
                itemId: 'filter-context',
                emptyText: _('filter_by_context'),
                width: 180,
                allowBlank: true,
                value: MODx.request.context || null,
                baseParams: {
                    action: 'Context/GetList',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupContext',
                    usergroup: config.usergroup
                },
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.updateDependentFilter('filter-policy-context', 'context', record.data.key);
                            this.applyGridFilter(cmp, 'context');
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'modx-combo-policy',
                itemId: 'filter-policy-context',
                emptyText: _('filter_by_policy'),
                width: 180,
                allowBlank: true,
                value: this.applyRequestFilter(0),
                baseParams: {
                    action: 'Security/Access/Policy/GetList',
                    group: 'Administrator,Context,Object',
                    isGridFilter: true,
                    targetGrid: 'MODx.grid.UserGroupContext',
                    usergroup: config.usergroup
                },
                listeners: {
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
    MODx.grid.UserGroupContext.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.UserGroupContext, MODx.grid.UserGroupBase);
Ext.reg('modx-grid-user-group-context', MODx.grid.UserGroupContext);

/**
 * @class MODx.window.CreateUGAccessContext
 * @extends MODx.window.UserGroupAclBase
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-context-create
 */
MODx.window.CreateUGAccessContext = function CreateUGAccessContext(config = {}) {
    this.aclType = 'context';
    this.windowId = config.ident || `${this.aclType}-access-create-${Ext.id()}`;
    this.idPrefix = `modx-${this.windowId}`;
    Ext.applyIf(config, {
        saveMode: 'create',
        action: 'Security/Access/UserGroup/Context/Create',
        fields: this.getWindowFields()
    });
    MODx.window.CreateUGAccessContext.superclass.constructor.call(this, config);
};

Ext.extend(MODx.window.CreateUGAccessContext, MODx.window.UserGroupAclBase);
Ext.reg('modx-window-user-group-context-create', MODx.window.CreateUGAccessContext);

/**
 * @class MODx.window.UpdateUGAccessContext
 * @extends MODx.window.CreateUGAccessContext
 * @param {Object} config An object of options
 * @xtype modx-window-user-group-context-update
 */
MODx.window.UpdateUGAccessContext = function UpdateUGAccessContext(config = {}) {
    this.aclType = 'context';
    this.windowId = config.ident || `${this.aclType}-access-update-${Ext.id()}`;
    Ext.applyIf(config, {
        saveMode: 'update',
        action: 'Security/Access/UserGroup/Context/Update'
    });
    MODx.window.UpdateUGAccessContext.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUGAccessContext, MODx.window.CreateUGAccessContext);
Ext.reg('modx-window-user-group-context-update', MODx.window.UpdateUGAccessContext);
