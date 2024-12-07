/**
 * Loads the Users panel
 *
 * @class MODx.panel.Users
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-users
 */
MODx.panel.Users = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-users',
        cls: 'container',
        bodyStyle: '',
        defaults: { collapsible: false, autoHeight: true },
        items: [{
            html: _('users'),
            id: 'modx-users-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('users'),
            layout: 'form',
            items: [{
                html: `<p>${_('user_management_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-user',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }])]
    });
    MODx.panel.Users.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Users, MODx.FormPanel);
Ext.reg('modx-panel-users', MODx.panel.Users);

/**
 * Loads a grid of User.
 *
 * @class MODx.grid.User
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user
 */
MODx.grid.User = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/User/GetList',
            usergroup: MODx.request.usergroup || null
        },
        fields: [
            'id',
            'username',
            'fullname',
            'email',
            'gender',
            'blocked',
            'role',
            'active'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/User/UpdateFromGrid',
        autosaveErrorMsg: _('user_err_save'),
        remoteSort: true,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            scrollOffset: 0,
            autoFill: true,
            showPreview: true,
            getRowClass: function(record) {
                return record.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        },
        sm: this.sm,
        columns: [this.sm, {
            header: _('id'),
            dataIndex: 'id',
            width: 50,
            sortable: true
        }, {
            header: _('username'),
            dataIndex: 'username',
            width: 150,
            sortable: true,
            renderer: {
                fn: function(value, metaData, record) {
                    return this.userCanEditRecord(record)
                        ? this.renderLink(value, {
                            href: `?a=security/user/update&id=${record.data.id}`,
                            title: _('user_edit_account')
                        })
                        : value
                    ;
                },
                scope: this
            }
        }, {
            header: _('user_full_name'),
            dataIndex: 'fullname',
            width: 180,
            sortable: true,
            editor: { xtype: 'textfield' },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return value;
                },
                scope: this
            }
        }, {
            header: _('email'),
            dataIndex: 'email',
            width: 180,
            sortable: true,
            editor: {
                xtype: 'textfield'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return value;
                },
                scope: this
            }
        }, {
            header: _('active'),
            dataIndex: 'active',
            width: 80,
            sortable: true,
            editor: {
                xtype: 'combo-boolean'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    const
                        displayValue = this.rendYesNo(value, metaData),
                        classes = `${metaData.css} ${this.setEditableCellClasses(record)}`
                    ;
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = classes;
                    return displayValue;
                },
                scope: this
            }
        }, {
            header: _('user_block'),
            dataIndex: 'blocked',
            width: 80,
            sortable: true,
            editor: {
                xtype: 'combo-boolean'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    const
                        displayValue = this.rendYesNo(value, metaData),
                        classes = `${metaData.css} ${this.setEditableCellClasses(record)}`
                    ;
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = classes;
                    return displayValue;
                },
                scope: this
            }
        }],
        tbar: [
            this.getCreateButton('user', 'createUser'),
            this.getBulkActionsButton('user', 'Security/User/RemoveMultiple', 'int', 'activate', 'deactivate'),
            '->',
            {
                xtype: 'modx-combo-usergroup',
                itemId: 'filter-usergroup',
                emptyText: `${_('user_group')}...`,
                baseParams: {
                    action: 'Security/Group/GetList',
                    addAll: true
                },
                value: MODx.request.usergroup || null,
                width: 200,
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'usergroup');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(),
            this.getClearFiltersButton('filter-usergroup, filter-query')
        ]
    });
    MODx.grid.User.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate', 'activate'];

    this.setUserCanEdit(['edit_user', 'save_user']);
    this.setUserCanCreate(['new_user', 'save_user']);
    this.setUserCanDelete(['delete_user']);
    this.setShowActionsMenu();

    this.on({
        beforeedit: function(e) {
            if (!this.userCanEdit || !this.userCanEditRecord(e.record)) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.User, MODx.grid.Grid, {
    getMenu: function() {
        const menu = [];
        if (this.getSelectionModel().getCount() > 1) {
            if (this.userCanEdit) {
                menu.push({
                    text: _('selected_activate'),
                    handler: this.activateSelected,
                    scope: this
                });
                menu.push({
                    text: _('selected_deactivate'),
                    handler: this.deactivateSelected,
                    scope: this
                });
            }
            if (this.userCanDelete) {
                menu.push('-');
                menu.push({
                    text: _('selected_remove'),
                    handler: this.removeSelected.bind(this, 'user', 'Security/User/RemoveMultiple')
                });
            }
        } else {
            if (this.userCanEdit) {
                menu.push({
                    text: _('edit'),
                    handler: this.updateUser
                });
            }
            if (this.userCanCreate) {
                if (menu.length > 0) { menu.push('-'); }
                menu.push({
                    text: _('duplicate'),
                    handler: this.duplicateUser
                });
            }
            if (this.userCanDelete) {
                if (menu.length > 0) { menu.push('-'); }
                menu.push({
                    text: _('delete'),
                    handler: this.removeUser
                });
            }
        }
        return menu;
    },

    createUser: function() {
        MODx.loadPage('security/user/create');
    },

    updateUser: function() {
        MODx.loadPage('security/user/update', `id=${this.menu.record.id}`);
    },

    duplicateUser: function() {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/User/Duplicate',
                id: this.menu.record.id
            },
            listeners: {
                success: { fn: this.refresh, scope: this }
            }
        });
    },

    removeUser: function() {
        MODx.msg.confirm({
            title: _('delete'),
            text: _('user_confirm_remove'),
            url: this.config.url,
            params: {
                action: 'Security/User/Delete',
                id: this.menu.record.id
            },
            listeners: {
                success: { fn: this.refresh, scope: this }
            }
        });
    },

    activateSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/User/ActivateMultiple',
                users: selections
            },
            listeners: {
                success: {
                    fn: function() {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    },

    deactivateSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/User/DeactivateMultiple',
                users: selections
            },
            listeners: {
                success: {
                    fn: function() {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    }
});
Ext.reg('modx-grid-user', MODx.grid.User);
