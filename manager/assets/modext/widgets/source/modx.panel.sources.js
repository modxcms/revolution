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
                urlFilters: ['query'],
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
        }], {
            stateful: true,
            stateId: 'modx-sources-tabpanel',
            stateEvents: ['tabchange'],
            getState: function() {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            }
        })]
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
MODx.grid.Sources = function(config) {

    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Source/GetList'
        },
        fields: [
            'id',
            'name',
            'description',
            'class_key',
            'cls'
        ],
        paging: true,
        autosave: true,
        save_action: 'Source/UpdateFromGrid',
        remoteSort: true,
        sm: this.sm,
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
                allowBlank: false
            },
            renderer: {
                fn: function(value, metaData, record) {
                    if (
                        // !this.userCanEdit ||
                        !this.userCanEditRecord(record.data) ||
                        this.recordIsProtected(record.data[this.protectedDataIndex], this.protectedIdentifiers)
                    ) {
                        metaData.css = 'editor-disabled';
                        return value;
                    }
                    return this.renderLink(value, {
                        href: '?a=source/update&id=' + record.data.id
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
                    console.log('desc record: ', record);
                    if (
                        // !this.userCanEdit ||
                        !this.userCanEditRecord(record.data) ||
                        this.recordIsProtected(record.data[this.protectedDataIndex], this.protectedIdentifiers)
                    ) {
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
                        console.log('bulk remove cb click, btn:', btn);
                        // console.log('bulk remove cb click, args:', arguments);
                        const   removableSources = this.getRemovableItemsFromSelection('int'),
                                menuOptRemove = btn.menu.getComponent('modx-bulk-menu-opt-remove')
                        ;
                        if (!removableSources) {
                            console.log('disabling this menu item: ',menuOptRemove);
                            menuOptRemove.disable();
                        } else {
                            menuOptRemove.enable();
                        }
                    },
                    scope: this
                }
            }
        }, '->', {
            xtype: 'textfield',
            name: 'search',
            id: 'modx-source-search',
            cls: 'x-form-filter',
            emptyText: _('search_ellipsis'),
            value: MODx.request.query,
            listeners: {
                change: {
                    fn: function(cb, rec, ri) {
                        this.sourceSearch(cb, rec, ri);
                    },
                    scope: this
                },
                afterrender: {
                    fn: function(cb) {
                        if (MODx.request.query) {
                            this.sourceSearch(cb, cb.value);
                            MODx.request.query = '';
                        }
                    },
                    scope: this
                },
                render: {
                    fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: this.blur,
                            scope: cmp
                        });
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'button',
            text: _('filter_clear'),
            id: 'modx-filter-clear',
            cls: 'x-form-filter-clear',
            listeners: {
                click: {
                    fn: this.clearFilter,
                    scope: this
                },
                mouseout: {
                    fn: function(evt) {
                        this.removeClass('x-btn-focus');
                    }
                }
            }
        }],
        viewConfig: {
            forceFit: true,
            scrollOffset: 0,
            getRowClass: function(record, index, rowParams, store) {
                // Adds the returned class to the row container's css classes
                // console.log('viewConfig, record:', record);
                // console.log('viewConfig, this:', this.grid.store.data.items);
                return this.grid.protectedIdentifiers.includes(record.id) || !this.grid.userCanDeleteRecord(record.data) ? 'disable-selection' : '';
            }
        }
    });
    MODx.grid.Sources.superclass.constructor.call(this, config);

    this.protectedDataIndex = 'id';
    this.protectedIdentifiers = [1];
    this.nonRemoveableRecords = [];
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
            if (
                this.recordIsProtected(e.record.data[this.protectedDataIndex], this.protectedIdentifiers) ||
                !this.userCanEditRecord(e.record.data)
            ) {
                return false;
            }
        }
    });
    // console.log('sources grid, this', this);
    this.getStore().on({
        load: function(store, records, params){
            // console.log('store load, this', this);
            // console.log('store load, args', arguments);
            records.forEach(record => {
                const record = record.data;
                // console.log('record: ', record);
                // if (Ext.isEmpty(permissions) || permissions.indexOf('premove') === -1) {
                if (Ext.isEmpty(permissions) || !userCanDeleteRecord(record)) {
                    this.nonRemoveableRecords.push(record.data[this.protectedDataIndex]);
                }
            });
            console.log('records that can not be removed: ',this.nonRemoveableRecords);
        },
        scope: this
    });
};
Ext.extend(MODx.grid.Sources, MODx.grid.Grid, {

    getMenu: function() {
        const   rowData = this.getSelectionModel().getSelected().data,
                isProtected = this.recordIsProtected(rowData[this.protectedDataIndex], this.protectedIdentifiers),
                m = []
        ;
        // console.log('sources, row data: ',rowData);
        // if (this.userCanEdit && !isProtected && this.userCanEditRecord(rowData)) {
        if (this.userCanEdit && !isProtected && this.userCanEditRecord(rowData)) {
            m.push({
                text: _('edit'),
                handler: this.updateSource
            });
        }
        if (this.userCanCreate && this.userCanDuplicateRecord(rowData)) {
            m.push({
                text: _('duplicate'),
                handler: this.duplicateSource
            });
        }
        if (this.userCanDelete && !isProtected && this.userCanDeleteRecord(rowData)) {
            if (m.length > 0) {
                m.push('-');
            }
            m.push({
                text: _('delete'),
                handler: this.removeSource
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
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
        const removableSources = this.getRemovableItemsFromSelection('int');
        if (!removableSources) {
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

    sourceSearch: function(tf, newValue, oldValue) {
        var s = this.getStore();
        s.baseParams.query = newValue;
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    },

    clearFilter: function() {
        const store = this.getStore(),
            sourceSearch = Ext.getCmp('modx-source-search');
        store.baseParams = {
            action: 'Source/GetList'
        };
        MODx.request.query = '';
        sourceSearch.setValue('');
        this.replaceState();
        this.getBottomToolbar().changePage(1);
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
MODx.window.CreateSource = function(config) {
    config = config || {};
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
            blankText: _('source_err_ns_name'),
            validationEvent: 'change'
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
            validationEvent: 'change',
            value: MODx.config.default_media_source_type
        }],
        keys: []
    });
    MODx.window.CreateSource.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateSource, MODx.Window);
Ext.reg('modx-window-source-create', MODx.window.CreateSource);

MODx.grid.SourceTypes = function(config) {
    config = config || {};

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
