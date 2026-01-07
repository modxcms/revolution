/**
 * @class MODx.grid.DashboardWidgets
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-dashboard-widgets
 */
MODx.grid.DashboardWidgets = function(config = {}) {
    const queryValue = this.applyRequestFilter(1, 'query', 'tab', true);
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template(
            '<p class="desc">{description_trans}</p>'
        )
    });
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        id: 'modx-grid-dashboard-widgets',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Dashboard/Widget/GetList'
        },
        fields: [
            'id',
            'name',
            'name_trans',
            'description',
            'description_trans',
            'type',
            'content',
            'namespace',
            'lexicon',
            'size'
        ],
        paging: true,
        remoteSort: true,
        sm: this.sm,
        plugins: [this.exp],
        columns: [
            this.exp,
            this.sm,
            {
                header: _('id'),
                dataIndex: 'id',
                width: 50,
                sortable: true
            }, {
                header: _('name'),
                dataIndex: 'name_trans',
                width: 150,
                sortable: true,
                editable: false,
                renderer: {
                    fn: function(value, metaData, record) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                        return this.userCanEditRecord(record)
                            ? this.renderLink(value, {
                                href: `?a=system/dashboards/widget/update&id=${record.data.id}`,
                                title: _('dashboard_edit')
                            })
                            : value
                        ;
                    },
                    scope: this
                }
            }, {
                header: _('widget_type'),
                dataIndex: 'type',
                width: 80,
                sortable: true
            }, {
                header: _('widget_namespace'),
                dataIndex: 'namespace',
                width: 120,
                sortable: true,
                renderer: {
                    fn: function(value, metaData, record) {
                        // eslint-disable-next-line no-param-reassign
                        metaData.css = this.setEditableCellClasses(record, [record.json.isProtected]);
                        return value;
                    },
                    scope: this
                }
            }
        ],
        tbar: [
            this.getCreateButton('dashboard', 'createDashboard'),
            this.getBulkActionsButton('widget', 'System/Dashboard/Widget/RemoveMultiple'),
            '->',
            this.getQueryFilterField(`filter-query-dashboardWidgets:${queryValue}`),
            this.getClearFiltersButton('filter-query-dashboardWidgets')
        ]
    });
    MODx.grid.DashboardWidgets.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit', 'delete'];

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
Ext.extend(MODx.grid.DashboardWidgets, MODx.grid.Grid, {
    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            menu = [],
            canDelete = this.userCanDelete && this.userCanDeleteRecord(record)
        ;
        if (this.getSelectionModel().getCount() > 1 && canDelete) {
            menu.push({
                text: _('selected_remove'),
                handler: this.removeSelected,
                scope: this
            });
        } else {
            if (this.userCanEdit && this.userCanEditRecord(record)) {
                menu.push({
                    text: _('edit'),
                    handler: this.updateWidget
                });
            }
            if (canDelete) {
                if (menu.length > 0) {
                    menu.push('-');
                }
                menu.push({
                    text: _('delete'),
                    handler: this.confirm.createDelegate(this, ['System/Dashboard/Widget/Remove', 'widget_remove_confirm'])
                });
            }
        }
        return menu;
    },

    createDashboard: function() {
        MODx.loadPage('system/dashboards/widget/create');
    },

    updateWidget: function() {
        MODx.loadPage('system/dashboards/widget/update', `id=${this.menu.record.id}`);
    }
});
Ext.reg('modx-grid-dashboard-widgets', MODx.grid.DashboardWidgets);
