/**
 * Loads the Sources panel
 *
 * @class MODx.panel.Sources
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-sources
 */
MODx.panel.Sources = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-sources',
        cls: 'container',
        bodyStyle: '',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: _('sources'),
            id: 'modx-sources-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('sources'),
            layout: 'form',
            items: [{
                html: `<p>${_('sources.intro_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-sources',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }, {
            layout: 'form',
            title: _('source_types'),
            items: [{
                html: `<p>${_('source_types.intro_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-source-types',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }])]
    });
    MODx.panel.Sources.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Sources, MODx.FormPanel);
Ext.reg('modx-panel-sources', MODx.panel.Sources);

/**
 * Loads a grid of Sources.
 *
 * @class MODx.grid.Sources
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-sources
 */
MODx.grid.Sources = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        id: 'modx-grid-sources',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Source/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'class_key',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'Source/UpdateFromGrid',
        remoteSort: true,
        sm: this.sm,
        stateful: true,
        stateId: 'modx-grid-sources-state',
        columns: [this.sm, {
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
                xtype: 'textfield',
                allowBlank: false,
                blankText: _('source_err_ns_name'),
                validationEvent: 'change',
                validator: function(value) {
                    const grid = Ext.getCmp('modx-grid-sources'),
                          reserved = this.gridEditor.record.json.reserved.name
                    ;
                    if (grid.valueIsReserved(reserved, value)) {
                        const msg = _('source_err_name_reserved', { reservedName: value });
                        Ext.Msg.alert(_('error'), msg);
                        return false;
                    }
                    return true;
                }
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                    return this.userCanEditRecord(record)
                        ? this.renderLink(value, {
                            href: `?a=source/update&id=${record.data.id}`,
                            title: _('source_edit')
                        })
                        : value
                    ;
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 300,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                    return value;
                },
                scope: this
            }
        },
        this.getCreatorColumnConfig('source')
        ],
        tbar: [
            this.getCreateButton('source', {
                xtype: 'modx-window-source-create',
                blankValues: true
            }),
            this.getBulkActionsButton('source', 'Source/RemoveMultiple'),
            '->',
            this.getQueryFilterField(),
            this.getClearFiltersButton()
        ],
        viewConfig: this.getViewConfig()
    });
    MODx.grid.Sources.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    this.setUserCanEdit(['source_save', 'source_edit']);
    this.setUserCanCreate(['source_save']);
    this.setUserCanDelete(['source_delete']);
    this.setShowActionsMenu();

    this.on({
        beforeedit: function(e) {
            if (e.record.json.isProtected || !this.userCanEditRecord(e.record)) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.Sources, MODx.grid.Grid, {

    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            menu = []
        ;
        if (this.userCanEdit && this.userCanEditRecord(record)) {
            menu.push({
                text: _('edit'),
                handler: this.updateSource
            });
        }
        if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
            menu.push({
                text: _('duplicate'),
                handler: this.duplicateSource
            });
        }
        if (this.userCanDelete && this.userCanDeleteRecord(record)) {
            if (menu.length > 0) {
                menu.push('-');
            }
            menu.push({
                text: _('delete'),
                handler: this.confirm.createDelegate(this, ['Source/Remove', 'source_remove_confirm'])
            });
        }
        return menu;
    },

    updateSource: function() {
        MODx.loadPage('source/update', `id=${this.menu.record.id}`);
    },

    duplicateSource: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Source/Duplicate',
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
Ext.reg('modx-grid-sources', MODx.grid.Sources);

/**
 * Generates the create Source window.
 *
 * @class MODx.window.CreateSource
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-source-create
 */
MODx.window.CreateSource = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        autoHeight: true,
        action: 'Source/Create',
        formDefaults: {
            anchor: '100%',
            validationEvent: 'change',
            validateOnBlur: false
        },
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            allowBlank: false
        }, {
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            grow: true
        }, {
            name: 'class_key',
            xtype: 'modx-combo-source-type',
            fieldLabel: _('source_type'),
            allowBlank: false,
            value: MODx.config.default_media_source_type
        }],
        keys: []
    });
    MODx.window.CreateSource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateSource, MODx.Window);
Ext.reg('modx-window-source-create', MODx.window.CreateSource);

MODx.grid.SourceTypes = function(config = {}) {
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Source/Type/GetList'
        },
        fields: [
            'class',
            'name',
            'description'
        ],
        showActionsColumn: false,
        paging: true,
        remoteSort: true,
        columns: [{
            header: _('name'),
            dataIndex: 'name',
            width: 150,
            sortable: true,
            renderer: Ext.util.Format.htmlEncode
        }, {
            header: _('description'),
            dataIndex: 'description',
            width: 300,
            sortable: false,
            renderer: Ext.util.Format.htmlEncode
        }]
    });
    MODx.grid.SourceTypes.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.SourceTypes, MODx.grid.Grid);
Ext.reg('modx-grid-source-types', MODx.grid.SourceTypes);
