/**
 * @class MODx.panel.Dashboards
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-dashboards
 */
MODx.panel.Dashboards = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-dashboards',
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: _('dashboards'),
            id: 'modx-dashboards-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            layout: 'form',
            title: _('dashboards'),
            items: [{
                html: `<p>${_('dashboards.intro_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-dashboards',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }, {
            layout: 'form',
            title: _('widgets'),
            items: [{
                html: `<p>${_('widgets.intro_msg')}</p>`,
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-dashboard-widgets',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }])]
    });
    MODx.panel.Dashboards.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Dashboards, MODx.FormPanel);
Ext.reg('modx-panel-dashboards', MODx.panel.Dashboards);

/**
 * @class MODx.grid.Dashboards
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-dashboards
 */
MODx.grid.Dashboards = function(config = {}) {
    const queryValue = this.applyRequestFilter(0, 'query', 'tab', true);
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-dashboards',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Dashboard/GetList',
            usergroup: MODx.request.usergroup || null
        },
        fields: [
            'id',
            'name',
            'description',
            'creator'
        ],
        paging: true,
        autosave: true,
        save_action: 'System/Dashboard/UpdateFromGrid',
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
            width: 150,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false,
                blankText: _('dashboard_err_ns_name'),
                validationEvent: 'change',
                validator: function(value) {
                    const grid = Ext.getCmp('modx-grid-dashboards'),
                          reserved = this.gridEditor.record.json.reserved.name
                    ;
                    if (grid.valueIsReserved(reserved, value)) {
                        const msg = _('dashboard_err_name_reserved', { reservedName: value });
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
                            href: `?a=system/dashboards/update&id=${record.data.id}`,
                            title: _('dashboard_edit')
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
            sortable: false,
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
        this.getCreatorColumnConfig('dashboard')
        ],
        tbar: [
            this.getCreateButton('dashboard', 'createDashboard'),
            this.getBulkActionsButton('dashboard', 'System/Dashboard/RemoveMultiple'),
            '->',
            {
                xtype: 'modx-combo-usergroup',
                itemId: 'filter-usergroup',
                emptyText: _('user_group_filter'),
                baseParams: {
                    action: 'Security/Group/GetList',
                    addAll: true
                },
                value: MODx.request.usergroup || null,
                width: 200,
                listeners: {
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'usergroup');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(`filter-query:${queryValue}`),
            this.getClearFiltersButton('filter-usergroup, filter-query')
        ]
    });
    MODx.grid.Dashboards.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete', 'duplicate'];

    // Note there are currently no action-specific permissions for Dashboards
    this.setUserCanEdit(['dashboards']);
    this.setUserCanCreate(['dashboards']);
    this.setUserCanDelete(['dashboards']);
    this.setShowActionsMenu();

    this.on({
        beforerender: function(grid) {
            grid.view = new Ext.grid.GridView(grid.getViewConfig());
        }
    });
};
Ext.extend(MODx.grid.Dashboards, MODx.grid.Grid, {
    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            menu = []
        ;
        if (this.userCanEdit && this.userCanEditRecord(record)) {
            menu.push({
                text: _('edit'),
                handler: this.updateDashboard
            });
        }
        if (this.userCanCreate && this.userCanDuplicateRecord(record)) {
            menu.push({
                text: _('duplicate'),
                handler: this.duplicateDashboard
            });
        }
        if (this.userCanDelete && this.userCanDeleteRecord(record)) {
            if (menu.length > 0) {
                menu.push('-');
            }
            menu.push({
                text: _('delete'),
                handler: this.confirm.createDelegate(this, ['System/Dashboard/Remove', 'dashboard_remove_confirm'])
            });
        }
        return menu;
    },

    createDashboard: function() {
        MODx.loadPage('system/dashboards/create');
    },

    updateDashboard: function() {
        MODx.loadPage('system/dashboards/update', `id=${this.menu.record.id}`);
    },

    duplicateDashboard: function(btn, e) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'System/Dashboard/Duplicate',
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
Ext.reg('modx-grid-dashboards', MODx.grid.Dashboards);
