/**
 * Loads the panel for managing namespaces.
 *
 * @class MODx.panel.Namespaces
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-namespaces
 */
MODx.panel.Namespaces = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-namespaces',
        cls: 'container',
        bodyStyle: '',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [
            {
                html: _('namespaces'),
                id: 'modx-namespaces-header',
                xtype: 'modx-header'
            },
            MODx.getPageStructure([{
                title: _('namespaces'),
                layout: 'form',
                items: [
                    {
                        html: `<p>${_('namespaces_desc')}</p>`,
                        xtype: 'modx-description'
                    }, {
                        xtype: 'modx-grid-namespace',
                        cls: 'main-wrapper',
                        preventRender: true
                    }
                ]
            }])
        ]
    });
    MODx.panel.Namespaces.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Namespaces, MODx.FormPanel);
Ext.reg('modx-panel-namespaces', MODx.panel.Namespaces);

/**
 * Loads a grid for managing namespaces.
 *
 * @class MODx.grid.Namespace
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-namespace
 */
MODx.grid.Namespace = function(config = {}) {
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-namespaces',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Workspace/PackageNamespace/GetList'
        },
        fields: [
            'name',
            'path',
            'assets_path',
            'perm',
            'creator'
        ],
        anchor: '100%',
        paging: true,
        autosave: true,
        save_action: 'Workspace/PackageNamespace/UpdateFromGrid',
        primaryKey: 'name',
        remoteSort: true,
        sm: this.sm,
        columns: [this.sm, {
            header: _('name'),
            dataIndex: 'name',
            id: 'modx-namespace--name',
            width: 200,
            sortable: true,
            // because PK is name, allowing edit is tricky as implemented; leave for now
            listeners: {
                click: {
                    fn: function(column, grid, rowIndex, e) {
                        if (e.target.classList.contains('simulated-link')) {
                            this.updateNamespace(e);
                        }
                    },
                    scope: this
                }
            }
        }, {
            header: _('namespace_path'),
            dataIndex: 'path',
            id: 'modx-namespace--path',
            width: 500,
            sortable: false,
            editor: {
                xtype: 'textfield'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(
                        record,
                        [record.json.isProtected, record.json.isExtrasNamespace],
                        '',
                        false
                    );
                    return value;
                },
                scope: this
            }
        }, {
            header: _('namespace_assets_path'),
            dataIndex: 'assets_path',
            id: 'modx-namespace--assets_path',
            width: 500,
            sortable: false,
            editor: {
                xtype: 'textfield'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    // eslint-disable-next-line no-param-reassign
                    metaData.css = this.setEditableCellClasses(
                        record,
                        [record.json.isProtected, record.json.isExtrasNamespace],
                        '',
                        false
                    );
                    return value;
                },
                scope: this
            }
        }, {
            header: _('creator'),
            dataIndex: 'creator',
            id: 'modx-namespace--creator',
            width: 70,
            align: 'center'
        }],
        tbar: [{
            text: _('create'),
            handler: {
                xtype: 'modx-window-namespace-create',
                blankValues: true
            },
            cls: 'primary-button',
            scope: this
        }, {
            text: _('bulk_actions'),
            menu: [{
                text: _('selected_remove'),
                itemId: 'modx-bulk-menu-opt-remove',
                handler: this.removeSelected.createDelegate(this, ['namespace', 'Workspace/PackageNamespace/RemoveMultiple']),
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
                        const removableNamespaces = this.getRemovableItemsFromSelection(),
                              menuOptRemove = btn.menu.getComponent('modx-bulk-menu-opt-remove')
                        ;
                        if (removableNamespaces.length === 0) {
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
    MODx.grid.Namespace.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete'];

    this.setUserCanEdit(['namespaces']);
    this.setUserCanCreate(['namespaces']);
    this.setUserCanDelete(['namespaces']);
    this.setShowActionsMenu();

    this.on({
        render: function() {
            this.setEditableColumnAccess(
                ['modx-namespace--path', 'modx-namespace--assets_path']
            );
        },
        beforeedit: function(e) {
            return !(e.record.json.isProtected || e.record.json.isExtrasNamespace);
        }
    });
};
Ext.extend(MODx.grid.Namespace, MODx.grid.Grid, {

    getMenu: function() {
        const record = this.getSelectionModel().getSelected(),
              menu = []
        ;
        if (this.userCanEdit && this.userCanEditRecord(record)) {
            menu.push({
                text: _('edit'),
                handler: this.updateNamespace
            });
        }
        if (this.userCanDelete && !record.json.isProtected) {
            if (menu.length > 0) {
                menu.push('-');
            }
            menu.push({
                text: _('delete'),
                handler: this.remove.createDelegate(this, ['namespace_remove_confirm', 'Workspace/PackageNamespace/Remove'])
            });
        }
        return menu;
    },

    updateNamespace: function(e) {
        const
            record = this.getSelectionModel().getSelected().data,
            window = MODx.load({
                xtype: 'modx-window-namespace-update',
                record: record,
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            })
        ;
        window.setValues(record);
        window.show(e.target);
    },

    removeSelected: function() {
        const selections = this.getSelectedAsList();
        if (selections === false) {
            return false;
        }
        MODx.msg.confirm({
            title: _('selected_remove'),
            text: _('namespace_remove_multiple_confirm'),
            url: this.config.url,
            params: {
                action: 'Workspace/PackageNamespace/RemoveMultiple',
                namespaces: selections
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
    }
});
Ext.reg('modx-grid-namespace', MODx.grid.Namespace);
