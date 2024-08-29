/**
 * @class MODx.grid.UserGroupBase
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties

 */

const ACL_TYPES_CONFIG = {
    context: {
        actions: {
            remove: 'Security/Access/UserGroup/Context/Remove'
        },
        policyGroups: 'Administrator,Context,Object'
    },
    namespace: {
        actions: {
            remove: 'Security/Access/UserGroup/AccessNamespace/Remove'
        },
        policyGroups: 'Namespace'
    },
    source: {
        actions: {
            remove: 'Security/Access/UserGroup/Source/Remove'
        },
        policyGroups: 'MediaSource'
    },
    category: {
        actions: {
            remove: 'Security/Access/UserGroup/Category/Remove'
        },
        policyGroups: 'Element,Object'
    },
    resourcegroup: {
        actions: {
            remove: 'Security/Access/UserGroup/ResourceGroup/Remove'
        },
        policyGroups: 'Resource,Object'
    }
};

MODx.grid.UserGroupBase = function UserGroupBase(config = {}) {
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        plugins: [this.rowExpander],
        paging: true,
        hideMode: 'offsets',
        grouping: true,
        remoteGroup: true,
        groupBy: 'role_display',
        singleText: _('policy'),
        pluralText: _('policies'),
        sortBy: 'name',
        remoteSort: true
    });

    MODx.grid.UserGroupBase.superclass.constructor.call(this, config);

    this.addEvents('createAcl', 'updateAcl');

    this.on({
        createAcl: function(response) {
            if (response.a.response.status === 200) {
                this.refreshFilterOptions(this.gridFilterData);
            }
        },
        updateAcl: function(response) {
            if (response.a.response.status === 200) {
                this.refreshFilterOptions(this.gridFilterData);
            }
        },
        afterRemoveRow: function() {
            this.refreshFilterOptions(this.gridFilterData);
        }
    });
};
Ext.extend(MODx.grid.UserGroupBase, MODx.grid.Grid, {
    windows: {},
    getColumns: function(columns) {
        this.rowExpander = new Ext.grid.RowExpander({
            tpl: new Ext.XTemplate(
                `<div class="info-list">
                    <ul>
                        {[ values.permissions.split(',').map(item => '<li>' + item.trim() + '</li>').join('') ]}
                    </ul>
                </div>`
            ),
            lazyRender: false,
            enableCaching: false
        });
        return [this.rowExpander, ...columns];
    },
    getMenu: function() {
        const record = this.getSelectionModel().getSelected(),
              permissions = record.data.cls,
              menu = []
        ;
        if (this.getSelectionModel().getCount() > 1) {
            // Currently not allowing bulk actions for this grid
        } else {
            if (permissions.indexOf('pedit') !== -1) {
                menu.push({
                    text: _(`access_${this.aclType}_update`),
                    handler: this.updateAcl
                });
            }
            if (permissions.indexOf('premove') !== -1) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _(`access_${this.aclType}_remove`),
                    handler: this.remove.createDelegate(this, ['confirm_remove', ACL_TYPES_CONFIG[this.aclType].actions.remove])
                });
            }
        }

        if (menu.length > 0) {
            this.addContextMenuItem(menu);
        }
    },

    /**
     * @property {Function} createAcl Creates a new usergroup access entry for the given element (aclType, e.g., context)
     *
     * @param {Ext.Toolbar.Item} button Ext button component object (Add ...)
     * @param {Ext.EventObject} e
     * @returns {void}
     */
    createAcl: function(button, e) {
        const
            record = {
                principal: this.config.usergroup
            },
            windowName = `create-${this.aclType}-acl`
        ;
        if (!this.windows[windowName]) {
            this.windows[windowName] = MODx.load({
                xtype: `modx-window-user-group-${this.aclType}-create`,
                record: record,
                listeners: {
                    success: {
                        fn: response => {
                            this.refresh();
                            this.fireEvent('createAcl', response);
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows[windowName].setValues(record);
        this.windows[windowName].show(e.target);
    },

    /**
     * @property {Function} updateAcl Updates selected usergroup access entry for the given element (aclType, e.g., context)
     *
     * @param {Ext.Menu} menuItem Contextual menu item object (Edit ...)
     * @param {Ext.EventObject} e
     * @returns {void}
     */
    updateAcl: function(menuItem, e) {
        const
            { record } = this.menu,
            windowName = `update-${this.aclType}-acl`
        ;
        if (!this.windows[windowName]) {
            this.windows[windowName] = MODx.load({
                xtype: `modx-window-user-group-${this.aclType}-update`,
                record: record,
                listeners: {
                    success: {
                        fn: response => {
                            this.refresh();
                            this.fireEvent('updateAcl', response);
                        },
                        scope: this
                    }
                }
            });
            this.windows[windowName].record = record;
        } else {
            this.windows[windowName].record = record;
            this.windows[windowName].fireEvent('updateWindow', this.windows[windowName]);
        }
        this.windows[windowName].fp.items.items.filter(item => item.xtype.includes('modx-combo')).forEach((combo, i) => {
            setTimeout(() => {
                combo.getStore().load({
                    callback: (records, options, success) => {
                        combo.setValue(record[combo.name]);
                    }
                });
            }, i * 50);
        });
        this.windows[windowName].fp.items.items.filter(item => item.xtype === 'hidden').forEach(field => {
            field.setValue(record[field.name]);
        });
        this.windows[windowName].show(e.target);
    }
});

/**
 * @class MODx.window.UserGroupAclBase
 * @extends MODx.Window
 * @param {Object} config An object of options
 */
MODx.window.UserGroupAclBase = function UserGroupAclBase(config = {}) {
    Ext.applyIf(config, {
        /*
            Title lexicon key naming scheme:
            access_{context|namespace|category|source|resourcegroup|...}_{create|update}
        */
        title: _(`access_${this.aclType}_${config.saveMode}`),
        url: MODx.config.connector_url,
        forceLayout: true
    });

    MODx.window.UserGroupAclBase.superclass.constructor.call(this, config);

    this.on({
        show: {
            fn: window => {
                // Permissions list only relevant for update windows (during show event)
                if (this.config.saveMode === 'update') {
                    this.getPermissionsList(window);
                }
            },
            scope: this
        },
        updateWindow: {
            fn: window => {
                this.getPermissionsList(window);
            }
        }
    });
};

Ext.extend(MODx.window.UserGroupAclBase, MODx.Window, {

    /**
     * @property {Function} getWindowFields Compile ACL type-specific and common fields into one array
     *
     * @param {Array} fields A set of Ext field config objects unique to this ACL type
     * @param {Array} hiddenFields A set of Ext field config objects (for hidden fields) unique to this ACL type
     * @returns {Array} Compiled array of configuration objects
     */
    getWindowFields: function(fields = [], hiddenFields = []) {
        const
            contextDesc = _(`user_group_${this.aclType}_context_desc`),
            authorityDesc = _(`user_group_${this.aclType}_authority_desc`),
            policyDesc = _(`user_group_${this.aclType}_policy_desc`),
            fieldsConfig = [
                {
                    xtype: 'hidden',
                    name: 'id'
                }, {
                    xtype: 'hidden',
                    name: 'principal',
                    hiddenName: 'principal'
                }
            ];
        if (hiddenFields.length > 0) {
            fieldsConfig.push(...hiddenFields);
        }
        if (['context', 'resourcegroup', 'category'].includes(this.aclType)) {
            const
                contextComboName = this.aclType === 'context' ? 'target' : 'context_key',
                contextCombo = [{
                    xtype: 'modx-combo-context',
                    fieldLabel: _('context'),
                    description: MODx.expandHelp ? '' : contextDesc,
                    id: `${this.idPrefix}-context`,
                    displayField: 'name',
                    name: contextComboName,
                    hiddenName: contextComboName,
                    editable: false,
                    allowBlank: false,
                    anchor: '100%'
                }, {
                    xtype: 'box',
                    hidden: !MODx.expandHelp,
                    html: contextDesc,
                    cls: 'desc-under'
                }]
            ;
            // Three of the ACL types use the context combo, but in different positions
            if (this.aclType === 'context') {
                fieldsConfig.push(...contextCombo);
            } else {
                fieldsConfig.push(...fields, ...contextCombo);
            }
        // Otherwise, push fields unique to the ACL type
        } else if (fields.length > 0) {
            fieldsConfig.push(...fields);
        }

        // Add in all fields common to all ACL types
        fieldsConfig.push({
            xtype: 'modx-combo-authority',
            fieldLabel: _('minimum_role'),
            description: MODx.expandHelp ? '' : authorityDesc,
            id: `${this.idPrefix}-authority`,
            name: 'authority',
            displayField: 'name',
            value: 0,
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: authorityDesc,
            cls: 'desc-under'
        }, {
            xtype: 'modx-combo-policy',
            fieldLabel: _('policy'),
            description: MODx.expandHelp ? '' : policyDesc,
            id: `${this.idPrefix}-policy`,
            name: 'policy',
            hiddenName: 'policy',
            baseParams: {
                action: 'Security/Access/Policy/GetList',
                group: ACL_TYPES_CONFIG[this.aclType].policyGroups,
                combo: true
            },
            allowBlank: false,
            anchor: '100%',
            listeners: {
                select: {
                    fn: function(cmp, record, index) {
                        this.getPermissionsList(this, record);
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: policyDesc,
            cls: 'desc-under'
        }, {
            xtype: 'container',
            cls: 'info-list permissions hide-list',
            itemId: `${this.idPrefix}-permissions`,
            layout: 'form',
            defaults: {
                anchor: '100%',
                xtype: 'box'
            },
            items: [
                {
                    itemId: `${this.idPrefix}-permissions-list-label`,
                    cls: 'header',
                    autoEl: {
                        tag: 'strong'
                    }
                },
                {
                    itemId: `${this.idPrefix}-permissions-list`,
                    cls: 'content'
                }
            ]
        });
        return fieldsConfig;
    },

    /**
     * @property {Function} getPermissionsList Parse the currently-selected/saved policy permissions and display them
     *
     * @param {Ext.Window} window The create or update window object that contains the permissions list being displayed/updated
     * @param {Object} record An optional data object containing the current values with which to update the form and permissions list
     * @returns {void}
     */
    getPermissionsList: function(window, record = {}) {
        const
            permissions = record?.data?.permissions || window.record.permissions,
            permissionsListContainer = window.fp?.getComponent(`${this.idPrefix}-permissions`),
            permissionsListCmp = permissionsListContainer?.getComponent(`${this.idPrefix}-permissions-list`),
            permissionsListLabelCmp = permissionsListContainer?.getComponent(`${this.idPrefix}-permissions-list-label`)
        ;
        if (permissions) {
            const
                permissionsArray = !Array.isArray(permissions) ? permissions.split(',') : permissions,
                permissionsCount = permissionsArray.length,
                permissionsList = permissionsArray.map(item => `<li>${item.trim()}</li>`).join('')
            ;
            if (permissionsListLabelCmp) {
                permissionsListLabelCmp.update(`${_('permissions_in_policy')} <span>(${permissionsCount})</span>`);
            }
            if (permissionsListCmp) {
                permissionsListCmp.update(`<ul>${permissionsList}</ul>`);
            }
            if (permissionsCount > 0) {
                permissionsListContainer.removeClass('hide-list');
            }
        } else {
            permissionsListContainer.addClass('hide-list');
        }
    }
});
