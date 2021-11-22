/**
 * Loads a grid of Roles.
 *
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-role
 */
MODx.grid.Role = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('roles'),
        id: 'modx-grid-role',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Role/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'authority',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/Role/UpdateFromGrid',
        stateful: true,
        stateId: 'modx-grid-role-state',
        columns: [{
            header: _('id'),
            dataIndex: 'id',
            width: 50,
            sortable: true
        }, {
            header: _('name'),
            dataIndex: 'name',
            id: 'modx-role--name',
            width: 150,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false,
                blankText: _('role_err_ns_name'),
                validator: function(value) {
                    if (this.gridEditor.record.json.reserved.name.includes(value)) {
                        const msg = _('role_err_name_reserved', { reservedName: value });
                        Ext.Msg.alert(_('error'), msg);
                        return false;
                    } else {
                        return true;
                    }
                }
            },
            renderer: {
                fn: function(value, metaData, record) {
                    if (!this.userCanEdit || record.json.isProtected) {
                        metaData.css = 'editor-disabled';
                    }
                    value = record.json.isProtected ? record.json.name_trans : value ;
                    return Ext.util.Format.htmlEncode(value);
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            id: 'modx-role--description',
            width: 350,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    if (!this.userCanEdit || record.json.isProtected) {
                        metaData.css = 'editor-disabled';
                    }
                    value = record.json.isProtected ? record.json.description_trans : value ;
                    return Ext.util.Format.htmlEncode(value);
                },
                scope: this
            }
        }, {
            header: _('creator'),
            dataIndex: 'creator',
            id: 'modx-role--creator',
            width: 70,
            align: 'center',
            sortable: true
        }, {
            header: _('authority'),
            dataIndex: 'authority',
            id: 'modx-role--authority',
            width: 60,
            align: 'center',
            sortable: true,
            editor: {
                xtype: 'numberfield',
                allowNegative: false,
                allowDecimals: false,
                allowBlank: false,
                blankText: _('role_err_ns_authority'),
                maxValue: 9999
            },
            renderer: {
                fn: function(value, metaData, record) {
                    if (!this.userCanEdit || record.json.isProtected) {
                        metaData.css = 'editor-disabled';
                    }
                    return value;
                },
                scope: this
            }
        }],
        tbar: [{
            text: _('create'),
            cls: 'primary-button',
            handler: this.createRole,
            scope: this,
            listeners: {
                render: {
                    fn: function(btn) {
                        if (!this.userCanCreate) {
                            btn.hide();
                        }
                    },
                    scope: this
                }
            }
        }],
        viewConfig: {
            forceFit: true,
            scrollOffset: 0,
            getRowClass: function(record, index, rowParams, store) {
                // Adds the returned class to the row container's css classes
                return record.json.isProtected ? 'modx-protected-row' : '';
            }
        }
    });
    MODx.grid.Role.superclass.constructor.call(this, config);

    this.protectedDataIndex = 'name';
    this.protectedIdentifiers = ['Super User', 'Member'];
    this.gridMenuActions = ['delete'];

    this.setUserCanEdit(['save_role', 'edit_role']);
    this.setUserCanCreate(['save_role', 'new_role']);
    this.setUserCanDelete(['delete_role']);
    this.setShowActionsMenu();

    this.on({
        render: function() {
            this.setEditableColumnAccess(
                ['modx-role--name', 'modx-role--description', 'modx-role--authority']
            );
        },
        beforeedit: function(e) {
            if (e.record.json.isProtected) {
                return false;
            }
        }
    });

};
Ext.extend(MODx.grid.Role, MODx.grid.Grid, {

    getMenu: function() {
        const   record = this.getSelectionModel().getSelected(),
                m = []
        ;
        if (this.userCanDelete && !record.json.isProtected) {
            m.push({
                text: _('delete'),
                handler: this.remove.createDelegate(this, ['role_remove_confirm', 'Security/Role/Remove'])
            });
        }
        return m;
    },

    createRole: function(btn, e) {
        this.loadWindow(btn, e, {
            xtype: 'modx-window-role-create',
            listeners: {
                success: {
                    fn: function() {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    }

});
Ext.reg('modx-grid-role', MODx.grid.Role);

/**
 * @class MODx.window.CreateRole
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-role-create
 */
MODx.window.CreateRole = function(config) {
    config = config || {};
    this.ident = config.ident || 'crole' + Ext.id();
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Role/Create',
        fields: [{
            name: 'name',
            fieldLabel: _('name'),
            id: 'modx-' + this.ident + '-name',
            xtype: 'textfield',
            allowBlank: false,
            blankText: _('role_err_ns_name'),
            anchor: '100%'
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden',
            forId: 'modx-' + this.ident + '-name',
            html: _('role_desc_name'),
            cls: 'desc-under'
        }, {
            name: 'authority',
            fieldLabel: _('authority'),
            xtype: 'numberfield',
            id: 'modx-' + this.ident + '-authority',
            allowBlank: false,
            blankText: _('role_err_ns_authority'),
            allowNegative: false,
            value: 0,
            maxValue: 9999,
            anchor: '100%'
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden',
            forId: 'modx-' + this.ident + '-authority',
            html: _('role_desc_authority'),
            cls: 'desc-under'
        }, {
            name: 'description',
            fieldLabel: _('description'),
            id: 'modx-' + this.ident + '-description',
            xtype: 'textarea',
            anchor: '100%',
            grow: true
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden',
            forId: 'modx-' + this.ident + '-description',
            html: _('role_desc_description'),
            cls: 'desc-under'
        }],
        keys: []
    });
    MODx.window.CreateRole.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateRole, MODx.Window);
Ext.reg('modx-window-role-create', MODx.window.CreateRole);
