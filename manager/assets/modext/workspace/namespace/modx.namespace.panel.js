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
            'perm'
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
                        [record.json.isProtected],
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
                        [record.json.isProtected],
                        '',
                        false
                    );
                    return value;
                },
                scope: this
            }
        }
        ],
        tbar: [
            this.getCreateButton('namespace', {
                xtype: 'modx-window-namespace-create',
                blankValues: true
            }),
            this.getBulkActionsButton('namespace', 'Workspace/PackageNamespace/RemoveMultiple', 'string'),
            '->',
            this.getQueryFilterField(),
            this.getClearFiltersButton()
        ],
        viewConfig: this.getViewConfig()
    });
    MODx.grid.Namespace.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete'];

    // Note there are currently no action-specific permissions for Namespaces
    this.setUserCanEdit(['namespaces']);
    this.setUserCanCreate(['namespaces']);
    this.setUserCanDelete(['namespaces']);
    this.setShowActionsMenu();

    this.on({
        beforeedit: function(e) {
            if (!this.userCanEditRecord(e.record) || e.record.json.isProtected) {
                return false;
            }
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
        if (this.userCanDelete && this.userCanEditRecord(record)) {
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
    }
});
Ext.reg('modx-grid-namespace', MODx.grid.Namespace);
