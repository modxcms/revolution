/**
 * Loads the panel for managing access policies.
 *
 * @class MODx.panel.AccessPolicies
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-access-policies
 */
MODx.panel.AccessPolicies = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-access-policies',
        bodyStyle: '',
        defaults: { collapsible: false, autoHeight: true },
        items: [{
            html: _('policies'),
            id: 'modx-policies-header',
            xtype: 'modx-header'
        }, {
            layout: 'form',
            cls: 'main-wrapper',
            items: [{
                html: `<p>${_('policy_management_msg')}</p>`,
                border: false
            }, {
                xtype: 'modx-grid-access-policy',
                preventRender: true
            }]
        }]
    });
    MODx.panel.AccessPolicies.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.AccessPolicies, MODx.FormPanel);
Ext.reg('modx-panel-access-policies', MODx.panel.AccessPolicies);

/**
 * Loads a grid of modAccessPolicies.
 *
 * @class MODx.grid.AccessPolicy
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-policy
 */
MODx.grid.AccessPolicy = function(config = {}) {
    const queryValue = this.applyRequestFilter(2, 'query', 'tab', true);
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-access-policy',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Access/Policy/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'description_trans',
            'parent',
            'template',
            'template_name',
            'active_permissions',
            'total_permissions',
            'active_of',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/Access/Policy/UpdateFromGrid',
        remoteSort: true,
        sm: this.sm,
        columns: [this.sm, {
            header: _('policy_name'),
            dataIndex: 'name',
            width: 200,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            },
            sortable: true,
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                    return this.userCanEditRecord(record)
                        ? this.renderLink(value, {
                            href: `?a=security/access/policy/update&id=${record.data.id}`,
                            title: _('policy_edit')
                        })
                        : value
                    ;
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 375,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                    return record.json.description_trans || value;
                },
                scope: this
            }
        }, {
            header: _('policy_template'),
            dataIndex: 'template_name',
            width: 375,
            renderer: {
                fn: function(value, metaData, record) {
                    const objPermissions = record.json.permissions;
                    return !Ext.isEmpty(objPermissions) && objPermissions.updateTemplate === true
                        ? this.renderLink(value, {
                            href: `?a=security/access/policy/template/update&id=${record.data.template}`,
                            title: _('policy_template_edit'),
                            target: '_blank'
                        })
                        : value
                    ;
                },
                scope: this
            }
        },
        this.getCreatorColumnConfig('policy'),
        {
            header: _('active_permissions'),
            dataIndex: 'active_of',
            width: 100,
            editable: false
        }],
        tbar: [
            this.getCreateButton('policy', 'createPolicy'),
            {
                text: _('import'),
                scope: this,
                handler: this.importPolicy,
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
            },
            this.getBulkActionsButton('policy', 'Security/Access/Policy/RemoveMultiple'),
            '->',
            this.getQueryFilterField(`filter-query-policy:${queryValue}`),
            this.getClearFiltersButton('filter-query-policy')
        ],
        viewConfig: this.getViewConfig()
    });
    MODx.grid.AccessPolicy.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate', 'export'];

    this.setUserCanEdit(['policy_save', 'policy_edit']);
    this.setUserCanCreate(['policy_save', 'policy_new']);
    this.setUserCanDelete(['policy_delete']);
    this.setShowActionsMenu();

    this.on({
        beforeedit: function(e) {
            if (!this.userCanEdit || e.record.json.isProtected || !this.userCanEditRecord(e.record)) {
                return false;
            }
        },
        afteredit: function(e) {
            this.refresh();
        }
    });
};
Ext.extend(MODx.grid.AccessPolicy, MODx.grid.Grid, {
    getMenu: function() {
        const
            model = this.getSelectionModel(),
            record = model.getSelected(),
            menu = []
        ;
        if (model.getCount() > 1) {
            const records = model.getSelections();
            if (this.userCanDelete && this.userCanDeleteRecords(records)) {
                menu.push({
                    text: _('selected_remove'),
                    handler: this.removeSelected.bind(this, 'policy', 'Security/Access/Policy/RemoveMultiple')
                });
            }
        } else {
            if (this.userCanEdit && this.userCanEditRecord(record)) {
                menu.push({
                    text: _('edit'),
                    handler: this.editPolicy
                });
            }
            if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
                menu.push({
                    text: _('duplicate'),
                    handler: this.confirm.createDelegate(this, ['Security/Access/Policy/Duplicate', 'policy_duplicate_confirm'])
                });
            }
            if (menu.length > 0) {
                menu.push('-');
            }
            menu.push({
                text: _('export'),
                handler: this.exportPolicy
            });
            if (this.userCanDelete && this.userCanDeleteRecord(record)) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('delete'),
                    handler: this.confirm.createDelegate(this, ['Security/Access/Policy/Remove', 'policy_remove_confirm'])
                });
            }
        }
        return menu;
    },

    editPolicy: function(itm, e) {
        MODx.loadPage('security/access/policy/update', `id=${this.menu.record.id}`);
    },

    createPolicy: function(btn, e) {
        const { record } = this.menu;
        if (!this.windows.apc) {
            this.windows.apc = MODx.load({
                xtype: 'modx-window-access-policy-create',
                record: record,
                plugin: this.config.plugin,
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
        this.windows.apc.reset();
        this.windows.apc.show(e.target);
    },

    exportPolicy: function(btn, e) {
        const { id } = this.menu.record;
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Access/Policy/Export',
                id: id
            },
            listeners: {
                success: {
                    fn: function(r) {
                        window.location.href = `${this.config.url}?action=Security/Access/Policy/Export&download=1&id=${id}&HTTP_MODAUTH=${MODx.siteId}`;
                    },
                    scope: this
                }
            }
        });
    },

    importPolicy: function(btn, e) {
        const record = {};
        if (!this.windows.importPolicy) {
            this.windows.importPolicy = MODx.load({
                xtype: 'modx-window-policy-import',
                record: record,
                listeners: {
                    success: {
                        fn: function(o) {
                            this.refresh();
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows.importPolicy.reset();
        this.windows.importPolicy.setValues(record);
        this.windows.importPolicy.show(e.target);
    }
});
Ext.reg('modx-grid-access-policy', MODx.grid.AccessPolicy);

/**
 * Generates a window for creating Access Policies.
 *
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-policy-create
 */
MODx.window.CreateAccessPolicy = function(config = {}) {
    this.ident = config.ident || `window--create-policy-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Access/Policy/Create',
        fields: [{
            fieldLabel: _('name'),
            description: MODx.expandHelp ? '' : _('policy_desc_name'),
            name: 'name',
            xtype: 'textfield',
            allowBlank: false,
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_desc_name'),
            cls: 'desc-under'
        }, {
            fieldLabel: _('policy_template'),
            description: MODx.expandHelp ? '' : _('policy_desc_template'),
            name: 'template',
            hiddenName: 'template',
            xtype: 'modx-combo-access-policy-template',
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_desc_template'),
            cls: 'desc-under'
        }, {
            fieldLabel: _('description'),
            description: MODx.expandHelp ? '' : _('policy_desc_description'),
            name: 'description',
            xtype: 'textarea',
            anchor: '100%',
            height: 50
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_desc_description'),
            cls: 'desc-under'
        }, {
            name: 'class',
            xtype: 'hidden'
        }, {
            name: 'id',
            xtype: 'hidden'
        }],
        keys: []
    });
    MODx.window.CreateAccessPolicy.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateAccessPolicy, MODx.Window);
Ext.reg('modx-window-access-policy-create', MODx.window.CreateAccessPolicy);

/**
 * @class MODx.window.AccessPolicyTemplate
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-access-policy-template
 */
MODx.combo.AccessPolicyTemplate = function(config = {}) {
    Ext.applyIf(config, {
        name: 'template',
        hiddenName: 'template',
        fields: [
            'id',
            'name',
            'description',
            'description_trans'
        ],
        forceSelection: true,
        typeAhead: false,
        editable: false,
        allowBlank: false,
        pageSize: 20,
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Access/Policy/Template/GetList'
        },
        tpl: new Ext.XTemplate(`
            <tpl for=".">
                <div class="x-combo-list-item">
                    <span style="font-weight: bold">{name:htmlEncode}</span>
                    <p style="margin: 0; font-size: 11px; color: gray;">{description_trans:htmlEncode}</p>
                </div>
            </tpl>
        `)
    });
    MODx.combo.AccessPolicyTemplate.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.AccessPolicyTemplate, MODx.combo.ComboBox);
Ext.reg('modx-combo-access-policy-template', MODx.combo.AccessPolicyTemplate);

/**
 * @class MODx.window.ImportPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-policy-import
 */
MODx.window.ImportPolicy = function(config = {}) {
    this.ident = config.ident || `window--import-policy-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('import'),
        id: 'modx-window-policy-import',
        url: MODx.config.connector_url,
        action: 'Security/Access/Policy/Import',
        fileUpload: true,
        saveBtnText: _('import'),
        fields: [{
            html: _('policy_import_msg'),
            xtype: 'modx-description',
            style: 'margin-bottom: 10px;'
        }, {
            xtype: 'fileuploadfield',
            fieldLabel: _('file'),
            buttonText: _('upload.buttons.upload'),
            name: 'file',
            anchor: '100%'
        }]
    });
    MODx.window.ImportPolicy.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.ImportPolicy, MODx.Window);
Ext.reg('modx-window-policy-import', MODx.window.ImportPolicy);
