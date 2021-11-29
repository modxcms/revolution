/**
 * Loads the Sources panel
 *
 * @class MODx.panel.Sources
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-sources
 */
MODx.panel.Sources = function(config) {
    config = config || {};
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
                html: '<p>' + _('sources.intro_msg') + '</p>',
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
                html: '<p>' + _('source_types.intro_msg') + '</p>',
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
            id: 'modx-source--name',
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
                    } else {
                        return true;
                    }
                }
            },
            renderer: {
                fn: function(value, metaData, record) {
                    value = value || record.json.name_trans;
                    const userCanEdit = this.userCanEdit && this.userCanEditRecord(record);
                    if (!userCanEdit || record.json.name === 'Filesystem') {
                        metaData.css = 'editor-disabled';
                        if (!userCanEdit) {
                            return value;
                        }
                    }
                    return this.renderLink(value, {
                        href: '?a=source/update&id=' + record.data.id,
                        title: _('source_edit')
                    });
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            id: 'modx-source--description',
            width: 300,
            sortable: false,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    value = value || record.json.description_trans;
                    const userCanEdit = this.userCanEdit && this.userCanEditRecord(record);
                    if (!userCanEdit || record.json.name === 'Filesystem') {
                        metaData.css = 'editor-disabled';
                    }
                    return value;
                },
                scope: this
            }
        }, {
            header: _('creator'),
            dataIndex: 'creator',
            id: 'modx-source--creator',
            width: 70,
            align: 'center',
            sortable: true
        }],
        tbar: [{
            text: _('create'),
            cls: 'primary-button',
            handler: {
                xtype: 'modx-window-source-create',
                blankValues: true
            },
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
            text: _('bulk_actions'),
            menu: [{
                text: _('selected_remove'),
                itemId: 'modx-bulk-menu-opt-remove',
                handler: this.removeSelected.createDelegate(this,['source','Source/RemoveMultiple','int']),
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
                        const removableSources = this.getRemovableItemsFromSelection('int'),
                              menuOptRemove = btn.menu.getComponent('modx-bulk-menu-opt-remove')
                        ;
                        if (removableSources.length === 0) {
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
        this.getQueryFilterField(),
        this.getClearFiltersButton()
        ],
        viewConfig: {
            forceFit: true,
            scrollOffset: 0,
            getRowClass: function(record, index, rowParams, store) {
                // Adds the returned class to the row container's css classes
                if (this.grid.userCanDeleteRecord(record)) {
                    return '';
                }
                const rowClasses = 'disable-selection';
                return record.json.isProtected ? `modx-protected-row  ${rowClasses}` : rowClasses ;
            }
        }
    });
    MODx.grid.Sources.superclass.constructor.call(this, config);

    this.protectedDataIndex = 'id';
    this.protectedIdentifiers = [1];
    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    this.setUserCanEdit(['source_save', 'source_edit']);
    this.setUserCanCreate(['source_save']);
    this.setUserCanDelete(['source_delete']);
    this.setShowActionsMenu();

    this.on({
        render: function(grid) {
            this.setEditableColumnAccess(
                ['modx-source--name', 'modx-source--description']
            );
        },
        beforeedit: function(e){
            // if (e.record.json.name === 'Filesystem' || !this.userCanEditRecord(e.record)) {
            if (e.record.json.isProtected || !this.userCanEditRecord(e.record)) {
                return false;
            }
        }
    });

    this.getStore().on({
        load: function(store, records, params){
            records.forEach(record => {
                if (!this.userCanDeleteRecord(record)) {
                    this.nonRemoveableRecords.push(record.id);
                }
            });
        },
        scope: this
    });

};
Ext.extend(MODx.grid.Sources, MODx.grid.Grid, {

    getMenu: function() {
        const   record = this.getSelectionModel().getSelected(),
                m = []
        ;
        if (this.userCanEdit && this.userCanEditRecord(record)) {
            m.push({
                text: _('edit'),
                handler: this.updateSource
            });
        }
        if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
            m.push({
                text: _('duplicate'),
                handler: this.duplicateSource
            });
        }
        if (this.userCanDelete && this.userCanDeleteRecord(record)) {
            if (m.length > 0) {
                m.push('-');
            }
            m.push({
                text: _('delete'),
                handler: this.removeSource
            });
        }
        return m;
    },

    createSource: function() {
        MODx.loadPage('system/source/create');
    },

    updateSource: function() {
        MODx.loadPage('source/update', 'id=' + this.menu.record.id);
    },

    duplicateSource: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Source/Duplicate',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    removeSource: function() {
        MODx.msg.confirm({
            title: _('delete'),
            text: _('source_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Source/Remove',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    removeSelected: function() {
        /*
            getRemovableItemsFromSelection must be run here, as any non-removeable rows
            that get selected (simply by clicking anywhere on the row) can not be de-selected
            programmatically. This ensures that non-removeables are discarded before sending
            to the processor.
        */
        const removableSources = this.getRemovableItemsFromSelection('int');
        if (removableSources.length === 0) {
            return false;
        }
        MODx.msg.confirm({
            title: _('source_remove_multiple'),
            text: _('source_remove_multiple_confirm'),
            url: this.config.url,
            params: {
                action: 'Source/RemoveMultiple',
                sources: removableSources.join(',')
            },
            listeners: {
                success: {
                    fn: function(r) {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        return true;
    },

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
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            anchor: '100%',
            allowBlank: false,
            // blankText: _('source_err_ns_name'),
            // validationEvent: 'change'
        }, {
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            anchor: '100%',
            grow: true
        }, {
            name: 'class_key',
            hiddenName: 'class_key',
            xtype: 'modx-combo-source-type',
            fieldLabel: _('source_type'),
            anchor: '100%',
            allowBlank: false,
            // validationEvent: 'change',
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
