/**
 * @class MODx.panel.UserGroup
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-user-group
 */
MODx.panel.UserGroup = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-user-group',
        cls: 'container form-with-labels',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Group/Update'
        },
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [this.getPageHeader(config), {
            xtype: 'modx-tabs',
            defaults: {
                autoHeight: true,
                border: true,
                bodyCssClass: 'tab-panel-wrapper',
                hideMode: 'offsets'
            },
            id: 'modx-usergroup-tabs',
            forceLayout: true,
            deferredRender: false,
            items: [{
                title: _('general_information'),
                defaults: {
                    border: false,
                    msgTarget: 'side'
                },
                layout: 'form',
                itemId: 'modx-usergroup-general-panel',
                labelAlign: 'top',
                labelSeparator: '',
                items: [{
                    xtype: 'panel',
                    border: false,
                    cls: 'main-wrapper',
                    layout: 'form',
                    items: [{
                        layout: 'column',
                        border: false,
                        defaults: {
                            layout: 'form',
                            labelAlign: 'top',
                            labelSeparator: '',
                            anchor: '100%',
                            border: false
                        },
                        items: [{
                            columnWidth: 0.6,
                            items: [{
                                xtype: 'hidden',
                                name: 'id',
                                id: 'modx-usergroup-id',
                                value: config.record.id
                            }, {
                                name: 'name',
                                xtype: config.record && (config.record.name === 'Administrator' || config.record.id === 0) ? 'statictextfield' : 'textfield',
                                fieldLabel: _('name'),
                                allowBlank: false,
                                enableKeyEvents: true,
                                disabled: config.record.id === 0,
                                anchor: '100%',
                                listeners: {
                                    keyup: {
                                        fn: function(f, e) {
                                            Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                        },
                                        scope: this
                                    }
                                }
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('user_group_desc_name'),
                                cls: 'desc-under'
                            }, {
                                name: 'description',
                                xtype: 'textarea',
                                fieldLabel: _('description'),
                                anchor: '100%',
                                grow: true
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('user_group_desc_description'),
                                cls: 'desc-under'
                            }]
                        }, {
                            columnWidth: 0.4,
                            items: [{
                                name: 'parent',
                                hiddenName: 'parent',
                                xtype: 'modx-combo-usergroup',
                                fieldLabel: _('user_group_parent'),
                                editable: false,
                                anchor: '100%',
                                disabled: config.record.id === 0 || config.record.name === 'Administrator',
                                baseParams: {
                                    action: 'Security/Group/GetList',
                                    addNone: true,
                                    exclude: config.record.id
                                }
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('user_group_desc_parent'),
                                cls: 'desc-under'
                            }, {
                                name: 'dashboard',
                                xtype: 'modx-combo-dashboard',
                                fieldLabel: _('dashboard'),
                                anchor: '100%'
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('user_group_desc_dashboard'),
                                cls: 'desc-under'
                            }]
                        }]
                    }]
                }]
            }, {
                title: _('access_permissions'),
                itemId: 'modx-usergroup-permissions-panel',
                items: [{
                    xtype: 'modx-vtabs',
                    items: [{
                        title: _('user_group_context_access'),
                        itemId: 'user-group-context-access',
                        hideMode: 'offsets',
                        layout: 'form',
                        autoWidth: false,
                        items: [{
                            html: `<p>${_('user_group_context_access_msg')}</p>`,
                            xtype: 'modx-description'
                        }, {
                            xtype: 'modx-grid-user-group-context',
                            preventRender: true,
                            usergroup: config.record.id,
                            autoHeight: true,
                            cls: 'main-wrapper',
                            listeners: {
                                afterRemoveRow: { fn: this.markDirty, scope: this },
                                afteredit: { fn: this.markDirty, scope: this },
                                updateAcl: { fn: this.markDirty, scope: this },
                                createAcl: { fn: this.markDirty, scope: this }
                            }
                        }]
                    }, {
                        title: _('user_group_resourcegroup_access'),
                        itemId: 'user-group-resourcegroup-access',
                        hideMode: 'offsets',
                        layout: 'form',
                        items: [{
                            html: `<p>${_('user_group_resourcegroup_access_msg')}</p>`,
                            xtype: 'modx-description'
                        }, {
                            xtype: 'modx-grid-user-group-resource-group',
                            cls: 'main-wrapper',
                            preventRender: true,
                            usergroup: config.record.id,
                            autoHeight: true,
                            listeners: {
                                afterRemoveRow: { fn: this.markDirty, scope: this },
                                afteredit: { fn: this.markDirty, scope: this },
                                updateAcl: { fn: this.markDirty, scope: this },
                                createAcl: { fn: this.markDirty, scope: this }
                            }
                        }]
                    }, {
                        title: _('user_group_category_access'),
                        itemId: 'user-group-category-access',
                        hideMode: 'offsets',
                        layout: 'form',
                        items: [{
                            html: `<p>${_('user_group_category_access_msg')}</p>`,
                            xtype: 'modx-description'
                        }, {
                            xtype: 'modx-grid-user-group-category',
                            cls: 'main-wrapper',
                            preventRender: true,
                            usergroup: config.record.id,
                            autoHeight: true,
                            listeners: {
                                afterRemoveRow: { fn: this.markDirty, scope: this },
                                afteredit: { fn: this.markDirty, scope: this },
                                updateAcl: { fn: this.markDirty, scope: this },
                                createAcl: { fn: this.markDirty, scope: this }
                            }
                        }]
                    }, {
                        title: _('user_group_source_access'),
                        itemId: 'user-group-source-access',
                        hideMode: 'offsets',
                        layout: 'form',
                        items: [{
                            html: `<p>${_('user_group_source_access_msg')}</p>`,
                            xtype: 'modx-description'
                        }, {
                            xtype: 'modx-grid-user-group-source',
                            cls: 'main-wrapper',
                            preventRender: true,
                            usergroup: config.record.id,
                            autoHeight: true,
                            listeners: {
                                afterRemoveRow: { fn: this.markDirty, scope: this },
                                afteredit: { fn: this.markDirty, scope: this },
                                updateAcl: { fn: this.markDirty, scope: this },
                                createAcl: { fn: this.markDirty, scope: this }
                            }
                        }]
                    }, {
                        title: _('user_group_namespace_access'),
                        itemId: 'user-group-namespace-access',
                        hideMode: 'offsets',
                        layout: 'form',
                        items: [{
                            html: `<p>${_('user_group_namespace_access_desc')}</p>`,
                            xtype: 'modx-description'
                        }, {
                            xtype: 'modx-grid-user-group-namespace',
                            cls: 'main-wrapper',
                            preventRender: true,
                            usergroup: config.record.id,
                            autoHeight: true
                        }]
                    }],
                    listeners: {
                        render: function(vtabPanel) {
                            const
                                elCatsPanelKey = vtabPanel.items.keys.indexOf('user-group-category-access'),
                                mediaSrcPanelKey = vtabPanel.items.keys.indexOf('user-group-source-access'),
                                namespacePanelKey = vtabPanel.items.keys.indexOf('user-group-namespace-access'),
                                form = Ext.getCmp('modx-panel-user-group').getForm()
                            ;
                            if (form.record.id === 0) {
                                vtabPanel.hideTabStripItem(elCatsPanelKey);
                                vtabPanel.hideTabStripItem(mediaSrcPanelKey);
                                vtabPanel.hideTabStripItem(namespacePanelKey);
                            }
                        }
                    }
                }]
            }, {
                title: _('users'),
                itemId: 'modx-usergroup-users-panel',
                items: [{
                    html: `<p>${_('user_group_user_access_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-user-group-users',
                    cls: 'main-wrapper',
                    preventRender: true,
                    usergroup: config.record.id,
                    autoHeight: true,
                    listeners: {
                        afterRemoveRow: { fn: this.markDirty, scope: this },
                        updateRole: { fn: this.markDirty, scope: this },
                        addUser: { fn: this.markDirty, scope: this }
                    }
                }]
            }, {
                title: _('settings'),
                itemId: 'modx-usergroup-settings-panel',
                layout: 'form',
                items: [{
                    html: `<p>${_('user_group_settings_desc')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-group-settings',
                    cls: 'main-wrapper',
                    preventRender: true,
                    group: config.record.id,
                    autoHeight: true
                }]
            }],
            listeners: {
                render: function(tabPanel) {
                    const
                        usersPanelKey = tabPanel.items.keys.indexOf('modx-usergroup-users-panel'),
                        settingsPanelKey = tabPanel.items.keys.indexOf('modx-usergroup-settings-panel'),
                        form = Ext.getCmp('modx-panel-user-group').getForm()
                    ;
                    if (form.record.id === 0) {
                        tabPanel.hideTabStripItem(usersPanelKey);
                        tabPanel.hideTabStripItem(settingsPanelKey);
                    }
                    if (!MODx.perm.usergroup_user_list) {
                        tabPanel.hideTabStripItem(usersPanelKey);
                    }
                }
            }
        }],
        useLoadingMask: false,
        listeners: {
            setup: { fn: this.setup, scope: this },
            success: { fn: this.success, scope: this },
            beforeSubmit: { fn: this.beforeSubmit, scope: this }
        }
    });
    MODx.panel.UserGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.UserGroup, MODx.FormPanel, {
    initialized: false,
    setup: function() {
        if (this.initialized || this.config.usergroup === '' || this.config.usergroup === undefined) {
            this.fireEvent('ready');
            return false;
        }
        const { record } = this.config;
        this.getForm().setValues(record);
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(record.name));

        this.fireEvent('ready', record);
        MODx.fireEvent('ready');
        this.initialized = true;
    },
    beforeSubmit: function(o) {},
    success: function(o) {},
    getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-user-group-header', [{
            text: _('user_group_management'),
            href: MODx.getPage('security/permission')
        }]);
    }
});
Ext.reg('modx-panel-user-group', MODx.panel.UserGroup);

/**
 * @class MODx.grid.FCProfileUserGroups
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-user-group-users
 */
MODx.grid.UserGroupUsers = function(config = {}) {
    const
        /** @var targetTab This grid shows in one of two places as of 3.0.x, in the ACLs summary view, and within a specific group’s ACLs view (in different tabs) */
        targetTab = MODx.request.a === 'security/permission' ? 0 : 2,
        queryValue = this.applyRequestFilter(targetTab, 'query', 'tab', true)
    ;
    Ext.applyIf(config, {
        title: '',
        id: 'modx-grid-user-group-users',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Group/User/GetList',
            usergroup: config.usergroup
        },
        paging: true,
        grouping: true,
        remoteSort: true,
        groupBy: 'role_name',
        singleText: _('user'),
        pluralText: _('users'),
        sortBy: 'authority',
        sortDir: 'ASC',
        fields: [
            'id',
            'username',
            'role',
            'role_name',
            'authority'
        ],
        columns: [{
            header: _('username'),
            dataIndex: 'username',
            width: 175,
            sortable: true,
            renderer: {
                fn: function(value, metaData, record) {
                    return this.userCanEditUsers
                        ? this.renderLink(value, {
                            href: `?a=security/user/update&id=${record.id}`,
                            target: '_blank'
                        })
                        : value
                    ;
                },
                scope: this
            }
        }, {
            header: _('role'),
            dataIndex: 'role_name',
            width: 175,
            sortable: true,
            renderer: {
                fn: function(value, metaData, record) {
                    return this.userCanEditRoles
                        ? this.renderLink(value, {
                            href: `?a=security/permission&tab=1&role=${record.json.role}`,
                            target: '_blank'
                        })
                        : value
                    ;
                },
                scope: this
            }
        }],
        tbar: [
            /*
                Because visibility of these buttons is determined by non-standard
                and differing create permissions, not using base method getCreateButton()
                here; controlled by render listener below
             */
            {
                text: _('user_group_update'),
                id: 'modx-btn-user-group-edit',
                cls: 'primary-button',
                handler: this.updateUserGroup
            }, {
                text: _('user_group_user_add'),
                id: 'modx-btn-user-group-add-user',
                cls: 'primary-button',
                handler: this.addUser
            },
            '->',
            this.getQueryFilterField(`filter-query-users:${queryValue}`, 'user-group-users'),
            this.getClearFiltersButton('filter-query-users')
        ]
    });
    MODx.grid.UserGroupUsers.superclass.constructor.call(this, config);
    this.addEvents('updateRole', 'addUser');

    this.gridMenuActions = ['editGroupUsers'];
    this.setUserHasPermissions('editGroups', ['usergroup_edit', 'usergroup_save']);
    this.setUserHasPermissions('editGroupUsers', ['usergroup_user_edit']);
    this.setUserHasPermissions('editRoles', ['edit_role', 'save_role']);
    this.setUserHasPermissions('editUsers', ['edit_user', 'save_user']);
    this.setShowActionsMenu();

    this.on({
        render: grid => {
            const buttonsToHide = [];
            if (!this.userCanEditGroups || grid.ownerCt.id !== 'modx-tree-panel-usergroup') {
                buttonsToHide.push('modx-btn-user-group-edit');
            }
            if (!this.userCanEditGroupUsers) {
                buttonsToHide.push('modx-btn-user-group-add-user');
            }
            if (buttonsToHide.length > 0) {
                buttonsToHide.forEach(btnId => Ext.getCmp(btnId)?.hide());
            }
        }
    });
};
Ext.extend(MODx.grid.UserGroupUsers, MODx.grid.Grid, {
    getMenu: function() {
        const menu = [];
        if (this.userCanEditGroupUsers) {
            menu.push({
                text: _('user_role_update'),
                handler: this.updateRole
            });
            menu.push('-');
            menu.push({
                text: _('user_group_user_remove'),
                handler: this.removeUser
            });
        }
        return menu;
    },

    updateUserGroup: function() {
        const id = this.config.usergroup;
        MODx.loadPage('security/usergroup/update', `id=${id}`);
    },

    updateRole: function(btn, e) {
        const { record } = this.menu;
        record.usergroup = this.config.usergroup;
        record.user = record.id;

        this.loadWindow(btn, e, {
            xtype: 'modx-window-user-group-role-update',
            record: record,
            listeners: {
                success: {
                    fn: function(response) {
                        this.refresh();
                        this.fireEvent('updateRole', response);
                    },
                    scope: this
                }
            }
        });
    },

    addUser: function(btn, e) {
        const record = { usergroup: this.config.usergroup };

        if (!this.windows['modx-window-user-group-adduser']) {
            this.windows['modx-window-user-group-adduser'] = Ext.ComponentMgr.create({
                xtype: 'modx-window-user-group-adduser',
                record: record,
                grid: this,
                listeners: {
                    success: {
                        fn: function(response) {
                            this.refresh();
                            this.fireEvent('addUser', response);
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows['modx-window-user-group-adduser'].setValues(record);
        this.windows['modx-window-user-group-adduser'].show(e.target);
    },

    removeUser: function(btn, e) {
        const { record } = this.menu;
        MODx.msg.confirm({
            title: _('warning'),
            text: _('user_group_user_remove_confirm') || _('confirm_remove'),
            url: this.config.url,
            params: {
                action: 'Security/Group/User/Remove',
                user: record.id,
                usergroup: this.config.usergroup
            },
            listeners: {
                success: { fn: this.refresh, scope: this }
            }
        });
    }
});
Ext.reg('modx-grid-user-group-users', MODx.grid.UserGroupUsers);

/**
 * @class MODx.window.UpdateUserGroupRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-role-update
 */
MODx.window.UpdateUserGroupRole = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-window-user-group-role-update',
        title: _('user_group_user_update_role'),
        url: MODx.config.connector_url,
        action: 'Security/Group/User/Update',
        fields: [{
            xtype: 'hidden',
            name: 'usergroup',
            value: config.usergroup
        }, {
            xtype: 'hidden',
            name: 'user',
            value: config.user
        }, {
            xtype: 'modx-combo-usergrouprole',
            id: 'modx-uugr-role',
            name: 'role',
            fieldLabel: _('role')
        }]
    });
    MODx.window.UpdateUserGroupRole.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateUserGroupRole, MODx.Window);
Ext.reg('modx-window-user-group-role-update', MODx.window.UpdateUserGroupRole);

/**
 * @class MODx.window.AddUserToUserGroup
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-user-group-adduser
 */
MODx.window.AddUserToUserGroup = function(config = {}) {
    this.ident = config.ident || `auug${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('user_group_user_add'),
        url: MODx.config.connector_url,
        action: 'Security/Group/User/Create',
        fields: [{
            fieldLabel: _('user'),
            description: MODx.expandHelp ? '' : _('user_group_user_add_user_desc'),
            name: 'user',
            hiddenName: 'user',
            xtype: 'modx-combo-user',
            editable: true,
            typeAhead: true,
            allowBlank: false,
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('user_group_user_add_user_desc'),
            cls: 'desc-under'
        }, {
            fieldLabel: _('role'),
            description: MODx.expandHelp ? '' : _('user_group_user_add_role_desc'),
            name: 'role',
            hiddenName: 'role',
            xtype: 'modx-combo-role',
            allowBlank: false,
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('user_group_user_add_role_desc'),
            cls: 'desc-under'
        }, {
            name: 'usergroup',
            xtype: 'hidden'
        }]
    });
    MODx.window.AddUserToUserGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.AddUserToUserGroup, MODx.Window);
Ext.reg('modx-window-user-group-adduser', MODx.window.AddUserToUserGroup);
