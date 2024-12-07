/**
 * @class MODx.panel.ContentType
 * @extends MODx.FormPanel
 * @param {Object} config An object of options.
 * @xtype modx-panel-content-type
 */
MODx.panel.ContentType = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-content-type',
        cls: 'container',
        url: MODx.config.connector_url,
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: _('content_types'),
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('content_types'),
            layout: 'form',
            itemId: 'form',
            items: [{
                html: `<p>${_('content_type_desc')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-content-type',
                itemId: 'grid',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }])]
    });
    MODx.panel.ContentType.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.ContentType, MODx.FormPanel, {});
Ext.reg('modx-panel-content-type', MODx.panel.ContentType);

/**
 * Loads a grid of content types
 *
 * @class MODx.grid.ContentType
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-contenttype
 */
MODx.grid.ContentType = function(config = {}) {
    const binaryColumn = new Ext.ux.grid.CheckColumn({
        header: _('binary'),
        dataIndex: 'binary',
        width: 40,
        sortable: true
    });
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/ContentType/GetList'
        },
        autosave: true,
        save_action: 'System/ContentType/UpdateFromGrid',
        fields: [
            'id',
            'name',
            'mime_type',
            'file_extensions',
            'icon',
            'headers',
            'binary',
            'description',
            'creator'
        ],
        paging: true,
        remoteSort: true,
        plugins: binaryColumn,
        columns: [
            {
                header: _('id'),
                dataIndex: 'id',
                width: 50,
                sortable: true
            }, {
                header: _('name'),
                dataIndex: 'name',
                sortable: true,
                editor: { xtype: 'textfield' },
                renderer: {
                    fn: function(value, metaData, record) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                        return value;
                    },
                    scope: this
                }
            }, {
                header: _('description'),
                dataIndex: 'description',
                width: 200,
                editor: { xtype: 'textfield' },
                renderer: {
                    fn: function(value, metaData, record) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                        return value;
                    },
                    scope: this
                }
            }, {
                header: _('mime_type'),
                dataIndex: 'mime_type',
                width: 80,
                sortable: true,
                editor: { xtype: 'textfield' },
                renderer: {
                    fn: function(value, metaData, record) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                        return value;
                    },
                    scope: this
                }
            }, {
                header: _('file_extensions'),
                dataIndex: 'file_extensions',
                sortable: true,
                editor: { xtype: 'textfield' }
            }, {
                header: _('icon'),
                dataIndex: 'icon',
                sortable: false,
                editor: { xtype: 'textfield' },
                renderer: this.renderIconField.createDelegate(this, [this], true)
            },
            binaryColumn,
            {
                dataIndex: 'headers',
                hidden: true
            },
            this.getCreatorColumnConfig('content_types')
        ],
        tbar: [
            this.getCreateButton('content_types', 'newContentType')
        ]
    });
    MODx.grid.ContentType.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete'];

    // Note there are currently no action-specific permissions for Content Types
    this.setUserCanEdit(['content_types']);
    this.setUserCanCreate(['content_types']);
    this.setUserCanDelete(['content_types']);
    this.setShowActionsMenu();

    this.on({
        beforerender: function(grid) {
            grid.view = new Ext.grid.GridView(grid.getViewConfig(false));
        },
        beforeedit: function(e) {
            const skipProtectionFieldList = ['file_extensions', 'icon'];
            if ((e.record.json.isProtected && !skipProtectionFieldList.includes(e.field)) || !this.userCanEditRecord(e.record)) {
                return false;
            }
        }
    });
};
Ext.extend(MODx.grid.ContentType, MODx.grid.Grid, {
    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            menu = []
        ;
        if (this.userCanEdit && this.userCanEditRecord(record)) {
            menu.push({
                text: _('edit'),
                handler: this.updateContentType.createDelegate(this, [record], true)
            });
        }
        if (this.userCanDelete && this.userCanDeleteRecord(record)) {
            menu.push({
                text: _('delete'),
                handler: this.confirm.createDelegate(this, ['System/ContentType/Remove', _('content_type_remove_confirm')])
            });
        }
        return menu;
    },

    newContentType: function(btn, e) {
        const window = new MODx.window.CreateContentType({ grid: this });
        window.show(e.target);
    },

    updateContentType: function(btn, e, record) {
        const window = new MODx.window.UpdateContentType({ record: record, grid: this });
        window.show(e.target);
    },

    renderIconField: function(value, metaData, record) {
        return new Ext.XTemplate('<i class="icon icon-lg {icon:htmlEncode}"></i>&nbsp;&nbsp; {icon:htmlEncode}').apply(record.data);
    }
});
Ext.reg('modx-grid-content-type', MODx.grid.ContentType);

/**
 * Generates the ContentType window.
 *
 * @class MODx.window.ContentType
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-content-type-create
 */
MODx.window.CreateContentType = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        width: 600,
        url: MODx.config.connector_url,
        action: 'System/ContentType/Create',
        bwrapCssClass: 'x-window-with-tabs',
        fields: [{
            xtype: 'modx-tabs',
            items: [{
                title: _('content_type_main_tab'),
                layout: 'form',
                items: [{
                    xtype: 'modx-description',
                    id: 'modx-content-type-general-desc',
                    hidden: !config.isUpdate || !config.record?.json?.isProtected,
                    html: _('content_type_reserved_general_desc')
                }, {
                    layout: 'column',
                    border: false,
                    defaults: {
                        layout: 'form',
                        labelSeparator: ''
                    },
                    items: [{
                        columnWidth: 0.6,
                        defaults: {
                            msgTarget: 'under',
                            anchor: '100%',
                            validationEvent: 'change',
                            validateOnBlur: false
                        },
                        items: [{
                            xtype: 'hidden',
                            name: 'id'
                        }, {
                            fieldLabel: _('name'),
                            name: 'name',
                            xtype: 'textfield',
                            allowBlank: false,
                            readOnly: (config.isUpdate && config.record.json?.isProtected) || false
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('name_desc'),
                            cls: 'desc-under'
                        }, {
                            fieldLabel: _('mime_type'),
                            description: MODx.expandHelp ? '' : _('mime_type_desc'),
                            name: 'mime_type',
                            xtype: 'textfield',
                            allowBlank: false,
                            readOnly: (config.isUpdate && config.record.json?.isProtected) || false
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('mime_type_desc'),
                            cls: 'desc-under'
                        }]
                    }, {
                        columnWidth: 0.4,
                        defaults: {
                            msgTarget: 'under',
                            anchor: '100%',
                            validationEvent: 'change',
                            validateOnBlur: false
                        },
                        items: [{
                            fieldLabel: _('icon'),
                            description: MODx.expandHelp ? '' : _('icon_desc'),
                            name: 'icon',
                            xtype: 'textfield'
                        }, {
                            fieldLabel: _('file_extensions'),
                            description: MODx.expandHelp ? '' : _('file_extensions_desc'),
                            name: 'file_extensions',
                            xtype: 'textfield'
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('file_extensions_desc'),
                            cls: 'desc-under'
                        }]
                    }]
                }, {
                    xtype: 'xcheckbox',
                    hideLabel: true,
                    boxLabel: _('binary_desc'),
                    name: 'binary',
                    hiddenName: 'binary'
                }, {
                    fieldLabel: _('description'),
                    labelSeparator: '',
                    name: 'description',
                    xtype: 'textarea',
                    anchor: '100%',
                    grow: true,
                    readOnly: (config.isUpdate && config.record.json?.isProtected) || false
                }, {
                    xtype: 'hidden',
                    name: 'headers'
                }]
            }, {
                title: _('content_type_header_tab'),
                layout: 'anchor',
                anchor: '100%',
                items: [{
                    xtype: 'modx-content-type-headers-grid',
                    id: 'headers'
                }]
            }]
        }],
        keys: []
    });
    MODx.window.CreateContentType.superclass.constructor.call(this, config);

    this.on({
        beforeSubmit: this.beforeSubmit,
        success: {
            fn: function() {
                this.grid.refresh();
            }
        }
    });
};
Ext.extend(MODx.window.CreateContentType, MODx.Window, {

    setRecord: function(record) {
        this.setValues(record);
        const
            grid = Ext.getCmp('headers'),
            store = grid.getStore()
        ;
        store.removeAll();
        if (record.headers && record.headers.length > 0) {
            Ext.each(record.headers, function(header) {
                store.add(new Ext.data.Record({
                    header: header
                }));
            }, this);
        }
    },

    beforeSubmit: function(o) {
        const
            grid = Ext.getCmp('headers'),
            store = grid.getStore(),
            records = store.getRange(),
            form = this.fp.getForm(),
            results = []
        ;
        Ext.each(records, function(rec) {
            results.push(rec.get('header'));
        }, this);

        form.findField('headers').setValue(Ext.encode(results));

        return true;
    }
});
Ext.reg('modx-window-content-type-create', MODx.window.CreateContentType);

MODx.window.UpdateContentType = function(config = {}) {
    Ext.applyIf(config, {
        title: _('edit'),
        action: 'System/ContentType/Update',
        isUpdate: true
    });
    MODx.window.UpdateContentType.superclass.constructor.call(this, config);
    this.setRecord(config.record.data);
};
Ext.extend(MODx.window.UpdateContentType, MODx.window.CreateContentType, {});

/**
 *
 * @param config
 * @constructor
 */
MODx.ContentTypeHeaderGrid = function(config = {}) {
    Ext.apply(config, {
        fields: ['id', 'header'],
        columns: [{
            header: _('content_type_header'),
            dataIndex: 'header'
        }],
        deferredRender: true,
        autoHeight: true,
        tbar: [this.getCreateButton('content_types', 'add', true)]
    });
    MODx.ContentTypeHeaderGrid.superclass.constructor.call(this, config);
};
Ext.extend(MODx.ContentTypeHeaderGrid, MODx.grid.LocalGrid, {
    add: function(btn, e) {
        const window = this.loadWindow();
        window.show(e.target);
    },

    edit: function(btn, e) {
        const
            { record } = this.menu,
            window = this.loadWindow(record)
        ;
        window.setValues(record);
        window.show(e.target);
    },

    remove: function() {
        const
            { record } = this.menu,
            store = this.getStore(),
            idx = store.find('header', record.header)
        ;
        store.removeAt(idx);
    },

    loadWindow: function(record) {
        return MODx.load({
            xtype: 'modx-window-content-header',
            grid: this,
            record: record
        });
    },

    getMenu: function() {
        const menu = [];
        menu.push({
            text: _('edit'),
            handler: this.edit,
            scope: this
        });

        menu.push({
            text: _('delete'),
            handler: this.remove,
            scope: this
        });

        return menu;
    }
});
Ext.reg('modx-content-type-headers-grid', MODx.ContentTypeHeaderGrid);

/**
 *
 * @param config
 * @constructor
 */
MODx.window.ContentHeader = function(config = {}) {
    Ext.apply(config, {
        title: _('content_type_header_title'),
        fields: [{
            xtype: 'textfield',
            name: 'header',
            fieldLabel: _('content_type_header'),
            anchor: '100%',
            allowBlank: false
        }],
        closeAction: 'close'
    });
    MODx.window.ContentHeader.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.ContentHeader, MODx.Window, {

    submit: function(close) {
        const
            values = this.fp.getForm().getValues(),
            store = this.grid.getStore()
        ;
        if (this.config.record && this.config.record.header) {
            // Existing record, let's update it
            const idx = store.find('header', this.config.record.header);
            store.removeAt(idx);
            store.insert(idx, new Ext.data.Record({
                header: values.header
            }));
        } else {
            // New record let's add it to the store
            store.add(new Ext.data.Record({
                header: values.header
            }));
        }

        this.close();
    }
});
Ext.reg('modx-window-content-header', MODx.window.ContentHeader);
