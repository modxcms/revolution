/**
 * Loads the panel for managing access policy templates.
 *
 * @class MODx.panel.AccessPolicyTemplates
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-access-policy-templates
 */
MODx.panel.AccessPolicyTemplates = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-access-policy-templates',
        bodyStyle: '',
        defaults: { collapsible: false, autoHeight: true },
        items: [{
            html: _('policies'),
            id: 'modx-policy-templates-header',
            xtype: 'modx-header'
        }, {
            layout: 'form',
            bodyStyle: 'padding: 15px',
            items: [{
                html: `<p>${_('policy_templates.intro_msg')}</p>`,
                border: false
            }, {
                xtype: 'modx-grid-access-policy-templates',
                preventRender: true
            }]
        }]
    });
    MODx.panel.AccessPolicyTemplates.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.AccessPolicyTemplates, MODx.FormPanel);
Ext.reg('modx-panel-access-policy-templates', MODx.panel.AccessPolicyTemplates);

/**
 * Loads a grid of modAccessPolicyTemplates.
 *
 * @class MODx.grid.AccessPolicyTemplates
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-policy
 */
MODx.grid.AccessPolicyTemplate = function(config = {}) {
    const queryValue = this.applyRequestFilter(3, 'query', 'tab', true);
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-access-policy-template',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Access/Policy/Template/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'description_trans',
            'template_group',
            'template_group_name',
            'total_permissions',
            'policy_count',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'Security/Access/Policy/Template/UpdateFromGrid',
        remoteSort: true,
        sm: this.sm,
        columns: [this.sm, {
            header: _('name'),
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
                            href: `?a=security/access/policy/template/update&id=${record.data.id}`,
                            title: _('policy_template_edit')
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
            header: _('template_group'),
            dataIndex: 'template_group_name',
            width: 375,
            sortable: true
        }, {
            header: _('policy_count'),
            dataIndex: 'policy_count',
            width: 100,
            editable: false,
            sortable: true
        }, {
            header: _('permissions'),
            dataIndex: 'total_permissions',
            width: 100,
            editable: false,
            sortable: true
        },
        this.getCreatorColumnConfig('policy-template')
        ],
        tbar: [
            this.getCreateButton('policy_template', 'createPolicyTemplate'),
            {
                text: _('import'),
                scope: this,
                handler: this.importPolicyTemplate,
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
            }, {
                /*
                 * Note: Using local this.removeSelected method instead of shared base this.getBulkActionsButton() method here,
                 * as additional validation processing is needed for removal of Policy Templates
                 */
                text: _('bulk_actions'),
                menu: [{
                    text: _('selected_remove'),
                    itemId: 'modx-bulk-menu-opt-remove',
                    handler: this.removeSelected,
                    scope: this
                }],
                listeners: {
                    render: {
                        fn: function(btn) {
                            if (!this.userCanDelete) {
                                btn.hide();
                            }
                        },
                        scope: this
                    },
                    click: {
                        fn: function(btn) {
                            const
                                removableItems = this.getRemovableItemsFromSelection('int'),
                                menuOptRemove = btn.menu.getComponent('modx-bulk-menu-opt-remove')
                            ;
                            if (removableItems.length === 0) {
                                menuOptRemove.disable();
                            } else {
                                menuOptRemove.enable();
                            }
                        },
                        scope: this
                    }
                }
            },
            '->',
            this.getQueryFilterField(`filter-query-policy-template:${queryValue}`),
            this.getClearFiltersButton('filter-query-policy-template')
        ],
        viewConfig: this.getViewConfig()
    });
    MODx.grid.AccessPolicyTemplate.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate', 'export'];

    this.setUserCanEdit(['policy_template_save', 'policy_template_edit']);
    this.setUserCanCreate(['policy_template_save', 'policy_template_new']);
    this.setUserCanDelete(['policy_template_delete']);
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
Ext.extend(MODx.grid.AccessPolicyTemplate, MODx.grid.Grid, {
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
                    handler: this.removeSelected
                });
            }
        } else {
            if (this.userCanEdit && this.userCanEditRecord(record)) {
                menu.push({
                    text: _('edit'),
                    handler: this.editPolicyTemplate
                });
            }
            if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
                menu.push({
                    text: _('duplicate'),
                    handler: this.confirm.createDelegate(
                        this,
                        [
                            'Security/Access/Policy/Template/Duplicate',
                            'policy_template_duplicate_confirm'
                        ]
                    )
                });
            }
            if (menu.length > 0) {
                menu.push('-');
            }
            menu.push({
                text: _('export'),
                handler: this.exportPolicyTemplate
            });
            if (this.userCanDelete && this.userCanDeleteRecord(record)) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                /*
                 * Note: Using local this.removePolicyTemplate method instead of shared base this.remove() method here,
                 * as additional validation processing is needed for removal of Policy Templates
                 */
                menu.push({
                    text: _('delete'),
                    handler: this.removePolicyTemplate
                });
            }
        }
        return menu;
    },

    createPolicyTemplate: function(btn, e) {
        const { record } = this.menu;
        if (!this.windows.create_policy_template) {
            this.windows.create_policy_template = MODx.load({
                xtype: 'modx-window-access-policy-template-create',
                record: record,
                plugin: this.config.plugin,
                listeners: {
                    success: {
                        fn: function(response) {
                            this.refresh();
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows.create_policy_template.reset();
        this.windows.create_policy_template.show(e.target);
    },

    importPolicyTemplate: function(btn, e) {
        const record = {};
        if (!this.windows.importPolicyTemplate) {
            this.windows.importPolicyTemplate = MODx.load({
                xtype: 'modx-window-policy-template-import',
                record: record,
                listeners: {
                    success: {
                        fn: function(response) {
                            this.refresh();
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows.importPolicyTemplate.reset();
        this.windows.importPolicyTemplate.setValues(record);
        this.windows.importPolicyTemplate.show(e.target);
    },

    exportPolicyTemplate: function(btn, e) {
        const { id } = this.menu.record;
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Access/Policy/Template/Export',
                id: id
            },
            listeners: {
                success: {
                    fn: function(r) {
                        window.location.href = `${this.config.url}?action=Security/Access/Policy/Template/Export&download=1&id=${id}&HTTP_MODAUTH=${MODx.siteId}`;
                    },
                    scope: this
                }
            }
        });
    },

    editPolicyTemplate: function(itm, e) {
        MODx.loadPage('security/access/policy/template/update', `id=${this.menu.record.id}`);
    },

    removeSelected: function() {
        const selectedTemplates = this.getSelectedAsList();
        if (selectedTemplates === false) {
            return false;
        }
        const
            store = this.getStore(),
            selectedTemplatesArr = selectedTemplates.split(','),
            totalSelected = selectedTemplatesArr.length
        ;
        let
            policiesCount = 0,
            selectionsProtected = 0,
            confirmationMessage
        ;
        selectedTemplatesArr.forEach(item => {
            const record = store.getById(item);
            if (record) {
                if (!record.json.isProtected) {
                    policiesCount += parseInt(record.data.policy_count, 10);
                } else {
                    selectionsProtected++;
                }
            }
        });
        if (policiesCount) {
            confirmationMessage = selectionsProtected > 0
                ? _('policy_template_remove_multiple_confirm_in_use_ignoring_protected', { 'count-policies': policiesCount, protected: selectionsProtected, 'count-templates': totalSelected })
                : _('policy_template_remove_multiple_confirm_in_use', { count: policiesCount, total: totalSelected })
            ;
        } else {
            confirmationMessage = _('policy_template_remove_multiple_confirm');
        }
        MODx.msg.confirm({
            title: _('selected_remove'),
            text: confirmationMessage,
            url: this.config.url,
            params: {
                action: 'Security/Access/Policy/Template/RemoveMultiple',
                templates: selectedTemplates
            },
            listeners: {
                success: {
                    fn: function(response) {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    },

    removePolicyTemplate: function() {
        if (!this.menu.record) {
            return;
        }
        MODx.msg.confirm({
            title: _('warning'),
            text: parseInt(this.menu.record.policy_count, 10)
                ? _('policy_template_remove_confirm_in_use', { count: this.menu.record.policy_count })
                : _('policy_template_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Security/Access/Policy/Template/Remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    }
});
Ext.reg('modx-grid-access-policy-templates', MODx.grid.AccessPolicyTemplate);

/**
 * Generates a window for creating Access Policies.
 *
 * @class MODx.window.CreateAccessPolicy
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-access-policy-create
 */
MODx.window.CreateAccessPolicyTemplate = function(config = {}) {
    this.ident = config.ident || `window-import-policy-template-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Access/Policy/Template/Create',
        fields: [{
            fieldLabel: _('name'),
            name: 'name',
            xtype: 'textfield',
            anchor: '100%'
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_template_desc_name'),
            cls: 'desc-under'
        }, {
            fieldLabel: _('template_group'),
            name: 'template_group',
            xtype: 'modx-combo-access-policy-template-group',
            anchor: '100%',
            value: 1
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_template_desc_template_group'),
            cls: 'desc-under'
        }, {
            fieldLabel: _('description'),
            name: 'description',
            xtype: 'textarea',
            anchor: '100%',
            height: 50
        }, {
            xtype: 'box',
            hidden: !MODx.expandHelp,
            html: _('policy_template_desc_description'),
            cls: 'desc-under'
        }],
        keys: []
    });
    MODx.window.CreateAccessPolicyTemplate.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateAccessPolicyTemplate, MODx.Window);
Ext.reg('modx-window-access-policy-template-create', MODx.window.CreateAccessPolicyTemplate);

/**
 * @class MODx.window.ImportPolicyTemplate
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-policy-template-import
 */
MODx.window.ImportPolicyTemplate = function(config = {}) {
    this.ident = config.ident || `window-import-policy-template-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('import'),
        id: 'modx-window-policy-template-import',
        url: MODx.config.connector_url,
        action: 'Security/Access/Policy/Template/Import',
        fileUpload: true,
        saveBtnText: _('import'),
        fields: [{
            html: _('policy_template_import_msg'),
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
    MODx.window.ImportPolicyTemplate.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.ImportPolicyTemplate, MODx.Window);
Ext.reg('modx-window-policy-template-import', MODx.window.ImportPolicyTemplate);
