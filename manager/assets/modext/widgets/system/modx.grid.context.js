/**
 * Loads the Contexts panel
 *
 * @class MODx.panel.Contexts
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-contexts
 */
MODx.panel.Contexts = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'modx-panel-contexts',
        cls: 'container',
        bodyStyle: '',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: _('contexts'),
            id: 'modx-contexts-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('contexts'),
            layout: 'form',
            items: [{
                html: '<p>' + _('context_management_message') + '</p>',
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-contexts',
                urlFilters: ['search'],
                cls: 'main-wrapper',
                preventRender: true
            }]
        }])]
    });
    MODx.panel.Contexts.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Contexts, MODx.FormPanel);
Ext.reg('modx-panel-contexts', MODx.panel.Contexts);

/**
 * Loads a grid of modContexts.
 *
 * @class MODx.grid.Context
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-contexts
 */
MODx.grid.Context = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('contexts'),
        id: 'modx-grid-context',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Context/GetList'
        },
        fields: [
            'key',
            'name',
            'description',
            'rank',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'Context/UpdateFromGrid',
        remoteSort: true,
        primaryKey: 'key',
        stateful: true,
        stateId: 'modx-grid-context-state',
        columns: [{
            header: _('key'),
            dataIndex: 'key',
            width: 100,
            sortable: true
        }, {
            header: _('name'),
            dataIndex: 'name',
            id: 'modx-context--name',
            width: 150,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false,
                blankText: _('context_err_ns_name'),
                validationEvent: 'change',
                validator: function(value) {
                    const   grid = Ext.getCmp('modx-grid-context'),
                            reserved = this.gridEditor.record.json.reserved.name
                    ;
                    if (grid.valueIsReserved(reserved, value)) {
                        const msg = _('context_err_name_reserved', { reservedName: value });
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
                    if (!userCanEdit || record.json.key === 'mgr') {
                        metaData.css = 'editor-disabled';
                        if (!userCanEdit) {
                            return value;
                        }
                    }
                    return this.renderLink(value, {
                        href: '?a=context/update&key=' + record.data.key,
                        title: _('context_edit')
                    });
                },
                scope: this
            }
        }, {
            header: _('description'),
            dataIndex: 'description',
            id: 'modx-context--description',
            width: 575,
            sortable: false,
            editor: {
                xtype: 'textarea'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    value = value || record.json.description_trans;
                    const userCanEdit = this.userCanEdit && this.userCanEditRecord(record);
                    if (!userCanEdit || record.json.key === 'mgr') {
                        metaData.css = 'editor-disabled';
                    }
                    return value;
                },
                scope: this
            }
        }, {
            header: _('creator'),
            dataIndex: 'creator',
            id: 'modx-context--creator',
            width: 70,
            align: 'center',
            sortable: true
        }, {
            header: _('rank'),
            dataIndex: 'rank',
            id: 'modx-context--rank',
            width: 100,
            align: 'center',
            sortable: true,
            editor: {
                xtype: 'numberfield'
            },
            renderer: {
                fn: function(value, metaData, record) {
                    const userCanEdit = this.userCanEdit && this.userCanEditRecord(record);
                    if (!userCanEdit || record.json.key === 'mgr') {
                        metaData.css = 'editor-disabled';
                    }
                    return value;
                },
                scope: this
            }
        }],
        tbar: [
            {
                text: _('create')
                ,cls:'primary-button'
                ,handler: this.create
                ,scope: this
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
                return record.json.isProtected ? 'modx-protected-row' : '';
            }
        }
    });
    MODx.grid.Context.superclass.constructor.call(this, config);

    this.protectedDataIndex = 'key';
    this.protectedIdentifiers = ['mgr', 'web'];
    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    this.setUserCanEdit(['save_context', 'edit_context']);
    this.setUserCanCreate(['save_context', 'new_context']);
    this.setUserCanDelete(['delete_context']);
    this.setShowActionsMenu();

    this.on({
        render: function() {
            this.setEditableColumnAccess(
                ['modx-context--name', 'modx-context--description', 'modx-context--rank']
            );
        },
        beforeedit: function(e) {
            if (e.record.json.key === 'mgr' || !this.userCanEditRecord(e.record)) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.Context, MODx.grid.Grid, {

    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            m = []
        ;

        if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
            m.push({
                text: _('duplicate'),
                handler: this.duplicateContext,
                scope: this
            });
        }

        if (this.userCanEdit && this.userCanEditRecord(record)) {
            m.push({
                text: _('edit'),
                handler: this.updateContext
            });
        }

        if (this.userCanDelete && this.userCanDeleteRecord(record)) {
            if (m.length > 0) {
                m.push('-');
            }
            m.push({
                text: _('delete'),
                handler: this.remove,
                scope: this
            });
        }
        return m;
    },

    create: function(btn, e) {
        if (this.createWindow) {
            this.createWindow.destroy();
        }
        this.createWindow = MODx.load({
            xtype: 'modx-window-context-create',
            closeAction: 'close',
            listeners: {
                success: {
                    fn: function() {
                        this.afterAction();
                    },
                    scope: this
                }
            }
        });
        this.createWindow.show(e.target);
    },

    updateContext: function(itm, e) {
        MODx.loadPage('context/update', 'key=' + this.menu.record.key);
    },

    duplicateContext: function() {
        var r = {
            key: this.menu.record.key,
            newkey: ''
        };
        var w = MODx.load({
            xtype: 'modx-window-context-duplicate',
            record: r,
            listeners: {
                success: {
                    fn: function() {
                        this.refresh();
                        var tree = Ext.getCmp('modx-resource-tree');
                        if (tree) {
                            tree.refresh();
                        }
                    },
                    scope: this
                }
            }
        });
        w.show();
    },

    remove: function(btn, e) {
        MODx.msg.confirm({
            title: _('warning'),
            text: _('context_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Context/Remove',
                key: this.menu.record.key
            },
            listeners: {
                success: {
                    fn: function() {
                        this.afterAction();
                    },
                    scope: this
                }
            }
        });
    },

    afterAction: function() {
        var cmp = Ext.getCmp('modx-resource-tree');
        if (cmp) {
            cmp.refresh();
        }
        this.getSelectionModel().clearSelections(true);
        this.refresh();
    }
});
Ext.reg('modx-grid-contexts', MODx.grid.Context);

/**
 * Generates the create context window.
 *
 * @class MODx.window.CreateContext
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-context-create
 */
MODx.window.CreateContext = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Context/Create',
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('context_key'),
            name: 'key',
            anchor: '100%',
            maxLength: 100,
            allowBlank: false,
            blankText: _('context_err_ns_key')
        }, {
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            anchor: '100%',
            maxLength: 100,
            allowBlank: false,
            blankText: _('context_err_ns_name')
        }, {
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            anchor: '100%',
            grow: true
        }, {
            xtype: 'numberfield',
            fieldLabel: _('rank'),
            name: 'rank',
            anchor: '100%'
        }],
        keys: []
    });
    MODx.window.CreateContext.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateContext, MODx.Window);
Ext.reg('modx-window-context-create', MODx.window.CreateContext);
