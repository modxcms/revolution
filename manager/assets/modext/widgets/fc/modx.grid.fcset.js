MODx.grid.FCSet = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    const actionCombo = new MODx.combo.FCAction();
    Ext.applyIf(config, {
        id: 'modx-grid-fc-set',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Forms/Set/GetList'
        },
        fields: [
            'id',
            'profile',
            'action',
            'description',
            'active',
            'template',
            'templatename',
            'constraint_data',
            'constraint',
            'constraint_field',
            'constraint_class',
            'rules'
        ],
        paging: true,
        autosave: true,
        preventSaveRefresh: false,
        save_action: 'Security/Forms/Set/UpdateFromGrid',
        sm: this.sm,
        remoteSort: true,
        autoExpandColumn: 'controller',
        columns: [this.sm, {
            header: _('id'),
            dataIndex: 'id',
            width: 40,
            sortable: true
        }, {
            header: _('template'),
            dataIndex: 'template',
            width: 150,
            sortable: true,
            renderer: {
                fn: function(value, metaData, record) {
                    let
                        displayValue = record.json.templatename,
                        linkDescripton = _('set_edit')
                    ;
                    if (Ext.isEmpty(record.json.templatename)) {
                        if (record.json.template > 0) {
                            displayValue = _('template_missing');
                            linkDescripton += `\n${_('template_missing_desc')}`;
                        } else {
                            displayValue = _('template_empty');
                            linkDescripton += `\n${_('template_empty_desc')}`;
                        }
                    }
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return this.renderLink(displayValue, {
                        href: `?a=security/forms/set/update&id=${record.id}`,
                        title: linkDescripton
                    });
                },
                scope: this
            }
        }, {
            header: _('action'),
            dataIndex: 'action',
            width: 200,
            sortable: true,
            editor: actionCombo,
            renderer: {
                fn: function(value, metaData, record, rowIndex, colIndex) {
                    const actionRecord = actionCombo.findRecord(actionCombo.valueField, value);
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record);
                    return actionRecord ? actionRecord.get(actionCombo.displayField) : value;
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 200,
            sortable: true,
            editor: {
                xtype: 'textarea'
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
            header: _('constraint_field'),
            dataIndex: 'constraint_field',
            width: 200,
            sortable: false,
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
            header: _('constraint'),
            dataIndex: 'constraint',
            width: 200,
            sortable: false,
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
        }],
        tbar: [
            this.getCreateButton('set', 'createSet'),
            this.getBulkActionsButton('set', 'Security/Forms/Set/RemoveMultiple', 'int', 'activate', 'deactivate'),
            {
                text: _('import'),
                handler: this.importSet,
                scope: this,
                listeners: {
                    render: {
                        fn: function(btn) {
                            if (!this.userCanEdit) {
                                btn.hide();
                            }
                        },
                        scope: this
                    }
                }
            },
            '->',
            this.getQueryFilterField(),
            this.getClearFiltersButton()
        ],
        viewConfig: this.getViewConfig(true, false, true)
    });
    MODx.grid.FCSet.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    // Note there are currently no action-specific, object-specific permissions for FC Sets
    this.setUserCanEdit(['customize_forms', 'save']);
    this.setUserCanCreate(['customize_forms', 'save']);
    this.setUserCanDelete(['customize_forms', 'remove']);
    this.setShowActionsMenu();

    this.on({
        beforeedit: function(e) {
            if (!this.userCanEdit) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.FCSet, MODx.grid.Grid, {
    getMenu: function() {
        const
            model = this.getSelectionModel(),
            record = model.getSelected(),
            menu = []
        ;
        if (model.getCount() > 1) {
            if (this.userCanEdit) {
                menu.push({
                    text: _('selected_activate'),
                    handler: this.activateSelected
                });
                menu.push({
                    text: _('selected_deactivate'),
                    handler: this.deactivateSelected
                });
            }
            if (this.userCanDelete) {
                menu.push('-');
                menu.push({
                    text: _('selected_remove'),
                    handler: this.removeSelected.bind(this, 'set', 'Security/Forms/Set/RemoveMultiple')
                });
            }
        } else {
            if (this.userCanEdit) {
                menu.push({
                    text: _('edit'),
                    handler: this.updateSet
                });
                menu.push({
                    text: _('duplicate'),
                    handler: this.duplicateSet
                });
                menu.push({
                    text: _('export'),
                    handler: this.exportSet
                });
                menu.push('-');
                if (record.data.active) {
                    menu.push({
                        text: _('deactivate'),
                        handler: this.deactivateSet
                    });
                } else {
                    menu.push({
                        text: _('activate'),
                        handler: this.activateSet
                    });
                }
            }
            if (this.userCanDelete) {
                menu.push('-', {
                    text: _('delete'),
                    handler: this.confirm.createDelegate(this, ['Security/Forms/Set/Remove', 'set_remove_confirm'])
                });
            }
        }
        return menu;
    },

    exportSet: function(btn, e) {
        const { id } = this.menu.record;
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Set/Export',
                id: id
            },
            listeners: {
                success: {
                    fn: function(response) {
                        window.location.href = `${this.config.url}?action=Security/Forms/Set/Export&download=${response.message}&id=${id}&HTTP_MODAUTH=${MODx.siteId}`;
                    },
                    scope: this
                }
            }
        });
    },

    importSet: function(btn, e) {
        const record = {
            profile: MODx.request.id
        };
        if (!this.windows.impset) {
            this.windows.impset = MODx.load({
                xtype: 'modx-window-fc-set-import',
                record: record,
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
        }
        this.windows.impset.reset();
        this.windows.impset.setValues(record);
        this.windows.impset.show(e.target);
    },

    createSet: function(btn, e) {
        const record = {
            profile: MODx.request.id,
            active: true
        };
        if (!this.windows.cset) {
            this.windows.cset = MODx.load({
                xtype: 'modx-window-fc-set-create',
                record: record,
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
        }
        this.windows.cset.reset();
        this.windows.cset.setValues(record);
        this.windows.cset.show(e.target);
    },

    updateSet: function(btn, e) {
        const { record } = this.menu;
        window.location.href = `?a=security/forms/set/update&id=${record.id}`;
    },

    duplicateSet: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'security/forms/set/duplicate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    activateSet: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Set/Activate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    deactivateSet: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Forms/Set/Deactivate',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
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
                action: 'Security/Forms/Set/ActivateMultiple',
                sets: selections
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
                action: 'Security/Forms/Set/DeactivateMultiple',
                sets: selections
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
Ext.reg('modx-grid-fc-set', MODx.grid.FCSet);

/**
 * @class MODx.window.CreateFCSet
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-fc-set-create
 */
MODx.window.CreateFCSet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Forms/Set/Create',
        width: 600,
        fields: [{
            xtype: 'hidden',
            name: 'profile',
            value: MODx.request.id
        }, {
            xtype: 'hidden',
            fieldLabel: _('constraint_class'),
            name: 'constraint_class',
            allowBlank: true,
            value: 'MODX\\Revolution\\modResource'
        }, {
            layout: 'column',
            border: false,
            defaults: {
                layout: 'form',
                labelAlign: 'top',
                border: false
            },
            items: [{
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under',
                    validationEvent: 'change',
                    validateOnBlur: false
                },
                items: [{
                    fieldLabel: _('action'),
                    name: 'action_id',
                    hiddenName: 'action_id',
                    id: 'modx-fcsc-action',
                    xtype: 'modx-combo-fc-action',
                    editable: false,
                    allowBlank: false
                }, {
                    xtype: 'textarea',
                    name: 'description',
                    fieldLabel: _('description'),
                    id: 'modx-fcsc-description'
                }, {
                    xtype: 'xcheckbox',
                    boxLabel: _('active'),
                    hideLabel: true,
                    name: 'active',
                    inputValue: 1,
                    value: 1,
                    checked: true
                }]
            }, {
                columnWidth: 0.5,
                defaults: {
                    anchor: '100%',
                    msgTarget: 'under',
                    validationEvent: 'change',
                    validateOnBlur: false
                },
                items: [{
                    xtype: 'modx-combo-template',
                    name: 'template',
                    hiddenName: 'template',
                    fieldLabel: _('template'),
                    description: MODx.expandHelp ? '' : _('set_template_desc'),
                    id: 'modx-fcsc-template',
                    baseParams: { action: 'Element/Template/GetList', combo: true }
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'modx-fcsc-template',
                    html: _('set_template_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('constraint_field'),
                    description: MODx.expandHelp ? '' : _('set_constraint_field_desc'),
                    name: 'constraint_field',
                    listeners: {
                        change: {
                            fn: function(cmp, newValue, oldValue) {
                                if (!Ext.isEmpty(newValue)) {
                                    const trimmedValue = newValue.trim();
                                    if (trimmedValue !== newValue) {
                                        cmp.setValue(trimmedValue);
                                    }
                                }
                            },
                            scope: this
                        }
                    }
                }, {
                    xtype: MODx.expandHelp ? 'box' : 'hidden',
                    html: _('set_constraint_field_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('constraint'),
                    description: MODx.expandHelp ? '' : _('set_constraint_desc'),
                    name: 'constraint',
                    listeners: {
                        change: {
                            fn: function(cmp, newValue, oldValue) {
                                if (!Ext.isEmpty(newValue)) {
                                    const trimmedValue = MODx.util.Format.trimCharSeparatedList(newValue);
                                    if (trimmedValue !== newValue) {
                                        cmp.setValue(trimmedValue);
                                    }
                                }
                            },
                            scope: this
                        }
                    }
                }, {
                    xtype: MODx.expandHelp ? 'box' : 'hidden',
                    html: _('set_constraint_desc'),
                    cls: 'desc-under'
                }]
            }]
        }],
        keys: []
    });
    MODx.window.CreateFCSet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateFCSet, MODx.Window);
Ext.reg('modx-window-fc-set-create', MODx.window.CreateFCSet);

/**
 * @class MODx.window.ImportFCSet
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-fc-set-import
 */
MODx.window.ImportFCSet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('import'),
        id: 'modx-window-fc-set-import',
        url: MODx.config.connector_url,
        action: 'Security/Forms/Set/Import',
        fileUpload: true,
        saveBtnText: _('import'),
        fields: [{
            xtype: 'hidden',
            name: 'profile',
            value: MODx.request.id
        }, {
            html: _('set_import_msg'),
            id: 'modx-impset-desc',
            xtype: 'modx-description',
            style: 'margin-bottom: 10px;'
        }, {
            xtype: 'fileuploadfield',
            fieldLabel: _('file'),
            buttonText: _('upload.buttons.upload'),
            name: 'file',
            id: 'modx-impset-file',
            anchor: '100%'
        }]
    });
    MODx.window.ImportFCSet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.ImportFCSet, MODx.Window);
Ext.reg('modx-window-fc-set-import', MODx.window.ImportFCSet);
