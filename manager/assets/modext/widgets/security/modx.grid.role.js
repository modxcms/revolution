/**
 * Loads a grid of roles.
 *
 * @class MODx.grid.Role
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-grid-role
 */
MODx.grid.Role = function(config = {}) {
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
            'perm'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/Role/UpdateFromGrid',
        columns: [{
            header: _('id'),
            dataIndex: 'id',
            width: 50,
            sortable: true
        }, {
            header: _('name'),
            dataIndex: 'name',
            width: 150,
            sortable: true,
            editor: {
                xtype: 'textfield'
            },
            renderer: {
                fn: function(value, metaData, record, rowIndex, colIndex, store) {
                    metaData.css = this.setEditableCellClasses(record);
                    return Ext.util.Format.htmlEncode(value);
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 350,
            editor: { xtype: 'textarea' },
            renderer: {
                fn: function(value, metaData, record, rowIndex, colIndex, store) {
                    metaData.css = this.setEditableCellClasses(record);
                    return Ext.util.Format.htmlEncode(value);
                },
                scope: this
            }
        }, {
            header: _('authority'),
            dataIndex: 'authority',
            width: 60,
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
                fn: function(value, metaData, record, rowIndex, colIndex, store) {
                    metaData.css = this.setEditableCellClasses(record, [record.json.isAssigned]);
                    return value;
                },
                scope: this
            },
            listeners: {
                dblclick: {
                    fn: function(column, grid, rowIndex, e) {
                        const
                            selectedRecord = grid.getSelectionModel().getSelected(),
                            roleIsAssigned = selectedRecord.json.isAssigned === 1
                        ;
                        if (roleIsAssigned) {
                            Ext.Msg.show({
                                title: _('warning'),
                                msg: _('role_warn_authority_locked'),
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.WARNING,
                                maxWidth: 400
                            });
                        }
                    },
                    scope: this
                }
            }
        }],
        tbar: [{
            text: _('create'),
            cls: 'primary-button',
            handler: this.createRole,
            scope: this
        }]
    });
    MODx.grid.Role.superclass.constructor.call(this, config);
    this.on('beforeedit', this.checkCellIsEditable, this);
};
Ext.extend(MODx.grid.Role, MODx.grid.Grid, {
    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            permissions = record.data.perm || '',
            menu = []
        ;
        if (permissions.indexOf('remove') !== -1) {
            menu.push({
                text: _('delete'),
                handler: this.remove.createDelegate(this, ['role_remove_confirm', 'Security/Role/Remove'])
            });
        }
        return menu;
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
MODx.window.CreateRole = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Role/Create',
        formDefaults: {
            allowBlank: false,
            anchor: '100%'
        },
        fields: [{
            name: 'name',
            fieldLabel: _('name'),
            xtype: 'textfield'
        }, {
            xtype: MODx.expandHelp ? 'box' : 'hidden',
            html: _('role_desc_name'),
            cls: 'desc-under'
        }, {
            name: 'authority',
            fieldLabel: _('authority'),
            xtype: 'textfield',
            allowNegative: false,
            value: 0
        }, {
            xtype: MODx.expandHelp ? 'box' : 'hidden',
            html: _('role_desc_authority'),
            cls: 'desc-under'
        }, {
            name: 'description',
            fieldLabel: _('description'),
            xtype: 'textarea',
            allowBlank: true,
            grow: true
        }, {
            xtype: MODx.expandHelp ? 'box' : 'hidden',
            html: _('role_desc_description'),
            cls: 'desc-under'
        }],
        keys: []
    });
    MODx.window.CreateRole.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateRole, MODx.Window);
Ext.reg('modx-window-role-create', MODx.window.CreateRole);
