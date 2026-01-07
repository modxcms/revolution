/**
 * Loads a grid of groups for a user
 *
 * @class MODx.grid.UserGroups
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-groups
 */
MODx.grid.UserGroups = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template(
            '<p class="desc">{user_group_desc}</p>'
        )
    });
    Ext.applyIf(config, {
        title: '',
        id: 'modx-grid-user-groups',
        /*
            url and baseParams are not utilized by the core when this
            grid is used (only in User > Access Permissions). Should remove
            if this class is not meant to be somehow used externally (via Extra)
        */
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Group/GetList'
        },
        fields: [
            'usergroup',
            'name',
            'member',
            'role',
            'rolename',
            'primary_group',
            'rank',
            'user_group_desc'
        ],
        cls: 'modx-grid modx-grid-draggable',
        columns: [
            this.exp,
            {
                header: _('user_group'),
                dataIndex: 'name',
                width: 175,
                renderer: {
                    fn: function(value, metaData, record) {
                        return this.userCanEditGroups
                            ? this.renderLink(value, {
                                href: `?a=security/usergroup/update&id=${record.data.usergroup}`,
                                target: '_blank'
                            })
                            : value
                        ;
                    },
                    scope: this
                }
            }, {
                header: _('role'),
                dataIndex: 'rolename',
                width: 175,
                renderer: {
                    fn: function(value, metaData, record) {
                        return this.userCanEditRoles
                            ? this.renderLink(value, {
                                href: `?a=security/permission&tab=1&role=${record.data.role}`,
                                target: '_blank'
                            })
                            : value
                        ;
                    },
                    scope: this
                }
            }, {
                header: _('rank'),
                dataIndex: 'rank',
                width: 80,
                editor: {
                    xtype: 'numberfield',
                    allowBlank: false,
                    allowNegative: false
                }
            }
        ],
        plugins: [this.exp],
        tbar: [
            this.getCreateButton('user_group_user', 'addGroup', 'userCanEditGroupUsers')
        ]
    });

    this.gridMenuActions = ['editGroupUsers'];
    this.setUserHasPermissions('editGroups', ['usergroup_edit', 'usergroup_save']);
    this.setUserHasPermissions('editGroupUsers', ['usergroup_user_edit']);
    this.setUserHasPermissions('editRoles', ['edit_role', 'save_role']);

    if (this.userCanEditGroupUsers) {
        config.plugins.push(
            new Ext.ux.dd.GridDragDropRowOrder({
                copy: false,
                scrollable: true,
                targetCfg: {},
                listeners: {
                    afterrowmove: {
                        fn: this.onAfterRowMove,
                        scope: this
                    },
                    /**
                     * @deprecated In 3.1, appears to be unused
                     */
                    beforerowmove: {
                        fn: this.onBeforeRowMove,
                        scope: this
                    }
                }
            })
        );
    }

    MODx.grid.UserGroups.superclass.constructor.call(this, config);

    this.userRecord = new Ext.data.Record.create([
        'usergroup',
        'name',
        'member',
        'role',
        'rolename',
        'primary_group'
    ]);
    this.addEvents(
        'beforeUpdateRole',
        'afterUpdateRole',
        'beforeAddGroup',
        'afterAddGroup',
        'beforeReorderGroup',
        'afterReorderGroup'
    );

    this.setShowActionsMenu();
};
Ext.extend(MODx.grid.UserGroups, MODx.grid.LocalGrid, {
    getMenu: function() {
        const menu = [];
        if (this.userCanEditGroupUsers) {
            menu.push({
                text: _('user_role_update'),
                handler: this.updateRole,
                scope: this
            });
            menu.push('-');
            menu.push({
                text: _('user_group_user_remove'),
                handler: this.remove.createDelegate(this, [{
                    text: _('user_group_user_remove_confirm')
                }]),
                scope: this
            });
        }
        return menu;
    },

    /**
     * @deprecated In 3.1, appears to be unused (including the beforeReorderGroup event)
     */
    onBeforeRowMove: function(dropTarget, fromRowIndex, toRowIndex, selections) {
        if (!this.fireEvent('beforeReorderGroup', {
            dt: dropTarget,
            sri: fromRowIndex,
            ri: toRowIndex,
            sels: selections
        })) {
            return false;
        }
        return true;
    },

    onAfterRowMove: function(dropTarget, fromRowIndex, toRowIndex, selections) {
        const
            store = this.getStore(),
            firstDraggedRecord = store.getAt(fromRowIndex),
            total = store.getTotalCount()
        ;
        firstDraggedRecord.set('rank', fromRowIndex);
        firstDraggedRecord.commit();

        /* get all rows below toRowIndex, and up their rank by 1 */
        for (let x = (toRowIndex - 1); x < total; x++) {
            const record = store.getAt(x);
            if (record) {
                record.set('rank', x);
                record.commit();
            }
        }
        this.fireEvent('afterReorderGroup');
        return true;
    },

    updateRole: function(btn, e) {
        const { record } = this.menu;
        record.user = this.config.user;
        this.fireEvent('beforeUpdateRole', record);

        this.loadWindow(btn, e, {
            xtype: 'modx-window-user-groups-role-update',
            record: record,
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            store = this.getStore(),
                            storeRecord = store.getAt(this.menu.recordIndex)
                        ;
                        storeRecord.set('role', response.role);
                        storeRecord.set('rolename', response.rolename);

                        this.fireEvent('afterUpdateRole', response);
                    },
                    scope: this
                }
            }
        });
    },

    addGroup: function(btn, e) {
        const record = {
            member: this.config.user
        };
        this.fireEvent('beforeUpdateRole', record);
        this.loadWindow(btn, e, {
            xtype: 'modx-window-user-addgroup',
            record: record,
            listeners: {
                success: {
                    fn: function(response) {
                        const
                            store = this.getStore(),
                            newRecord = new this.userRecord(response)
                        ;
                        store.add(newRecord);
                        this.fireEvent('afterAddGroup', response);
                    },
                    scope: this
                }
            }
        });
    }
});
Ext.reg('modx-grid-user-groups', MODx.grid.UserGroups);

/**
 * @class MODx.window.AddGroupToUser
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-addgroup
 */
MODx.window.AddGroupToUser = function(config = {}) {
    Ext.applyIf(config, {
        title: _('user_group_user_add'),
        url: MODx.config.connector_url,
        action: 'Security/Group/User/Create',
        fields: [{
            fieldLabel: _('user_group'),
            name: 'usergroup',
            hiddenName: 'usergroup',
            id: 'modx-agu-usergroup',
            xtype: 'modx-combo-usergroup',
            editable: false,
            allowBlank: false,
            anchor: '100%'
        }, {
            fieldLabel: _('role'),
            name: 'role',
            hiddenName: 'role',
            id: 'modx-agu-role',
            xtype: 'modx-combo-role',
            allowBlank: false,
            anchor: '100%'
        }, {
            name: 'member',
            xtype: 'hidden'
        }]
    });
    MODx.window.AddGroupToUser.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.AddGroupToUser, MODx.Window, {
    submit: function() {
        const
            record = this.fp.getForm().getValues(),
            groupsGrid = Ext.getCmp('modx-grid-user-groups'),
            store = groupsGrid.getStore(),
            recordExists = store.findExact('usergroup', record.usergroup)
        ;
        // Typecast user group ID (for strict match search)
        record.usergroup = parseInt(record.usergroup, 10);

        if (recordExists !== -1) {
            MODx.msg.alert(_('error'), _('user_err_ae_group'));
            return false;
        }

        record.rolename = Ext.getCmp('modx-agu-role').getRawValue();
        record.name = Ext.getCmp('modx-agu-usergroup').getRawValue();
        // Assume existing records have a correct rank
        record.rank = store.getCount();
        this.fireEvent('success', record);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-user-addgroup', MODx.window.AddGroupToUser);

/**
 * @class MODx.window.UpdateUserGroupsRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-groups-role-update
 */
MODx.window.UpdateUserGroupsRole = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-window-user-groups-role-update',
        title: _('user_group_user_update_role'),
        url: MODx.config.connector_url,
        action: 'Security/Group/User/Update',
        fields: [{
            xtype: 'hidden',
            name: 'user',
            value: config.user
        }, {
            xtype: 'modx-combo-role',
            id: 'modx-uugrs-role',
            name: 'role',
            fieldLabel: _('role'),
            anchor: '100%'
        }]
    });
    MODx.window.UpdateUserGroupsRole.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUserGroupsRole, MODx.Window, {
    submit: function() {
        const record = this.fp.getForm().getValues();
        record.rolename = Ext.getCmp('modx-uugrs-role').getRawValue();
        this.fireEvent('success', record);
        this.hide();
        return false;
    }
});
Ext.reg('modx-window-user-groups-role-update', MODx.window.UpdateUserGroupsRole);
