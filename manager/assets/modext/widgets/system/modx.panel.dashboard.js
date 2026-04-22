/**
 * @class MODx.panel.Dashboard
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-dashboard
 */
MODx.panel.Dashboard = function(config = {}) {
    let generalIntro = {};
    if (config.record.reserved) {
        generalIntro = {
            xtype: 'box',
            cls: 'panel-desc',
            html: _('dashboard_reserved_general_desc')
        };
    }
    Ext.applyIf(config, {
        id: 'modx-panel-dashboard',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Dashboard/Update'
        },
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [this.getPageHeader(config), {
            xtype: 'modx-tabs',
            defaults: {
                autoHeight: true,
                border: false
            },
            id: 'modx-dashboard-tabs',
            forceLayout: true,
            deferredRender: false,
            stateful: true,
            stateId: 'modx-dashboard-tabpanel',
            stateEvents: ['tabchange'],
            getState: function() {
                return { activeTab: this.items.indexOf(this.getActiveTab()) };
            },
            // todo: the layout is inconsistent with other panels, refactor the structure
            items: [{
                title: _('general_information'),
                cls: 'form-with-labels',
                defaults: {
                    border: false,
                    cls: 'main-wrapper'
                },
                layout: 'form',
                id: 'modx-dashboard-form',
                labelAlign: 'top',
                items: [generalIntro, {
                    xtype: 'hidden',
                    name: 'id',
                    id: 'modx-dashboard-id',
                    value: config.record.id
                }, {
                    layout: 'column',
                    border: false,
                    defaults: {
                        layout: 'form',
                        labelAlign: 'top',
                        labelSeparator: '',
                        border: false
                    },
                    items: [{
                        columnWidth: 0.7,
                        cls: 'main-content',
                        defaults: {
                            msgTarget: 'under',
                            anchor: '100%'
                        },
                        items: [{
                            xtype: config.record.reserved ? 'statictextfield' : 'textfield',
                            name: 'name',
                            fieldLabel: _('name'),
                            description: MODx.expandHelp ? '' : _('dashboard_name_desc'),
                            allowBlank: false,
                            enableKeyEvents: true,
                            listeners: {
                                keyup: {
                                    fn: function(f, e) {
                                        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                    },
                                    scope: this
                                }
                            }
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('dashboard_name_desc'),
                            cls: 'desc-under'
                        }, {
                            name: 'description',
                            xtype: 'textarea',
                            /**
                             * @todo - Change this xtype to the following once Lexicon-based name/desc is implemented for core dashboard
                             * xtype: config.record.reserved ? 'statictextfield' : 'textfield',
                             */
                            fieldLabel: _('description'),
                            description: MODx.expandHelp ? '' : _('dashboard_description_desc'),
                            grow: true
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('dashboard_description_desc'),
                            cls: 'desc-under'
                        }]
                    }, {
                        columnWidth: 0.3,
                        cls: 'main-content',
                        items: [{
                            name: 'hide_trees',
                            xtype: 'xcheckbox',
                            ctCls: 'display-switch',
                            boxLabel: _('dashboard_hide_trees'),
                            description: MODx.expandHelp ? '' : _('dashboard_hide_trees_desc'),
                            inputValue: 1
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('dashboard_hide_trees_desc'),
                            cls: 'desc-under'
                        }, {
                            name: 'customizable',
                            xtype: 'xcheckbox',
                            ctCls: 'display-switch',
                            boxLabel: _('dashboard_customizable'),
                            description: MODx.expandHelp ? '' : _('dashboard_customizable_desc'),
                            inputValue: 1,
                            checked: true
                        }, {
                            xtype: 'box',
                            hidden: !MODx.expandHelp,
                            html: _('dashboard_customizable_desc'),
                            cls: 'desc-under'
                        }]
                    }]
                }, {
                    html: `<p>${_('dashboard_widgets.intro_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-dashboard-widget-placements',
                    preventRender: true,
                    dashboard: config.record.id,
                    autoHeight: true,
                    anchor: '100%',
                    listeners: {
                        afterRemoveRow: { fn: this.markDirty, scope: this },
                        updateRole: { fn: this.markDirty, scope: this },
                        addMember: { fn: this.markDirty, scope: this }
                    }
                }]
            }]
        }],
        listeners: {
            setup: { fn: this.setup, scope: this },
            success: { fn: this.success, scope: this },
            beforeSubmit: { fn: this.beforeSubmit, scope: this }
        }
    });
    MODx.panel.Dashboard.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Dashboard, MODx.FormPanel, {
    initialized: false,

    setup: function() {
        if (this.initialized) { return false; }
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(this.config.record.name));

        const
            { widgets } = this.config.record,
            placementsGrid = Ext.getCmp('modx-grid-dashboard-widget-placements')
        ;
        if (widgets && placementsGrid) {
            placementsGrid.getStore().loadData(widgets);
        }

        this.fireEvent('ready', this.config.record);
        MODx.fireEvent('ready');
        this.initialized = true;
    },

    beforeSubmit: function(o) {
        const
            params = {},
            placementsGrid = Ext.getCmp('modx-grid-dashboard-widget-placements')
        ;
        if (placementsGrid) {
            params.widgets = placementsGrid.encode();
        }
        Ext.apply(o.form.baseParams, params);
    },

    success: function(o) {
        if (Ext.isEmpty(this.config.record) || Ext.isEmpty(this.config.record.id)) {
            MODx.loadPage('system/dashboards/update', `id=${o.result.object.id}`);
        } else {
            Ext.getCmp('modx-abtn-save').setDisabled(false);
            const wg = Ext.getCmp('modx-grid-dashboard-widget-placements');
            if (wg) { wg.getStore().commitChanges(); }
        }
    },

    getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-dashboard-header', [{
            text: _('dashboards'),
            href: MODx.getPage('system/dashboards')
        }]);
    }
});
Ext.reg('modx-panel-dashboard', MODx.panel.Dashboard);

/**
 * @class MODx.grid.DashboardWidgetPlacements
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-dashboard-widget-placements
 */
MODx.grid.DashboardWidgetPlacements = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template(
            '<p class="desc">{description_trans}</p>'
        )
    });
    Ext.applyIf(config, {
        id: 'modx-grid-dashboard-widget-placements',
        url: MODx.config.connector_url,
        fields: [
            'dashboard',
            'widget',
            'rank',
            'name',
            'name_trans',
            'description',
            'description_trans',
            'permissions'
        ],
        autoHeight: true,
        primaryKey: 'widget',
        cls: 'modx-grid modx-grid-draggable',
        plugins: [this.exp, new Ext.ux.dd.GridDragDropRowOrder({
            scrollable: true,
            targetCfg: {},
            listeners: {
                afterrowmove: {
                    fn: this.onAfterRowMove,
                    scope: this
                }
            }
        })],
        columns: [this.exp, {
            header: _('widget'),
            dataIndex: 'name_trans',
            width: 150,
            renderer: {
                fn: function(value, metaData, record) {
                    return this.renderLink(value, {
                        href: `?a=system/dashboards/widget/update&id=${record.data.widget}`,
                        target: '_blank'
                    });
                },
                scope: this
            }
        }, {
            header: _('rank'),
            dataIndex: 'rank',
            width: 80,
            align: 'center',
            editor: {
                xtype: 'numberfield',
                allowBlank: false,
                allowNegative: false
            }
        }],
        tbar: [{
            text: _('widget_place'),
            cls: 'primary-button',
            handler: this.placeWidget,
            scope: this
        }]
    });
    MODx.grid.DashboardWidgetPlacements.superclass.constructor.call(this, config);
    this.propRecord = Ext.data.Record.create([
        'dashboard',
        'widget',
        'rank',
        'name',
        'name_trans',
        'description',
        'description_trans',
        'permissions'
    ]);
};
Ext.extend(MODx.grid.DashboardWidgetPlacements, MODx.grid.LocalGrid, {
    getMenu: function() {
        return [{
            text: _('widget_unplace'),
            handler: this.unplaceWidget,
            scope: this
        }];
    },

    onAfterRowMove: function(dropTarget, fromRowIndex, toRowIndex, selections) {
        const
            store = this.getStore(),
            firstDraggedRecord = store.data.items[fromRowIndex],
            total = store.data.length
        ;
        firstDraggedRecord.set('rank', fromRowIndex);
        firstDraggedRecord.commit();

        // get all rows below toRowIndex, and up their rank by 1
        for (let x = (toRowIndex - 1); x < total; x++) {
            const record = store.data.items[x];
            if (record) {
                record.set('rank', x);
                record.commit();
            }
        }
        return true;
    },

    unplaceWidget: function(btn, e) {
        const
            selectedWidget = this.getSelectionModel().getSelected(),
            store = this.getStore(),
            selectedIndex = store.indexOf(selectedWidget),
            total = store.getTotalCount()
        ;
        for (let x = selectedIndex; x < total; x++) {
            const record = store.getAt(x);
            if (record) {
                record.set('rank', record.get('rank') - 1);
                record.commit();
            }
        }
        store.remove(selectedWidget);
    },

    placeWidget: function(btn, e) {
        if (!this.windows.placeWidget) {
            this.windows.placeWidget = MODx.load({
                xtype: 'modx-window-dashboard-widget-place',
                listeners: {
                    success: {
                        fn: function(widgetRecord) {
                            const newRecord = new this.propRecord(widgetRecord);
                            this.getStore().add(newRecord);
                        },
                        scope: this
                    }
                }
            });
        }
        this.windows.placeWidget.reset();
        this.windows.placeWidget.setValues({
            dashboard: this.config.dashboard
        });
        this.windows.placeWidget.show(btn);
    }
});
Ext.reg('modx-grid-dashboard-widget-placements', MODx.grid.DashboardWidgetPlacements);

/**
 * @class MODx.window.DashboardWidgetPlace
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-dashboard-widget-place
 */
MODx.window.DashboardWidgetPlace = function(config = {}) {
    this.ident = config.ident || `dbugadd${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('widget_place'),
        id: 'modx-window-dashboard-widget-place',
        fields: [{
            xtype: 'modx-combo-dashboard-widgets',
            fieldLabel: _('widget'),
            name: 'widget',
            hiddenName: 'widget',
            id: `modx-${this.ident}-widget`,
            allowBlank: false,
            msgTarget: 'under',
            anchor: '100%'
        }]
    });
    MODx.window.DashboardWidgetPlace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.DashboardWidgetPlace, MODx.Window, {
    submit: function() {
        const
            form = this.fp.getForm(),
            widgetField = form.findField('widget'),
            selectedWidget = widgetField.getValue(),
            widgetsGrid = Ext.getCmp('modx-grid-dashboard-widget-placements'),
            store = widgetsGrid.getStore()
        ;
        if (store.find('widget', selectedWidget) !== -1) {
            widgetField.markInvalid(_('dashboard_widget_err_placed'));
            return false;
        }
        const
            // Get the rank of the last record or set it to '0' if no record found
            rank = store.data.length > 0
                ? store.data.items[store.data.length - 1].get('rank') + 1
                : 0,
            widgetStore = widgetField.getStore(),
            widgetRowIndex = widgetStore.find('id', selectedWidget),
            record = widgetStore.getAt(widgetRowIndex)
        ;
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success', {
                widget: widgetField.getValue(),
                dashboard: widgetsGrid.config.dashboard,
                name: record.data.name,
                name_trans: record.data.name_trans,
                description: record.data.description,
                description_trans: record.data.description_trans,
                rank: rank
            })) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        } else {
            MODx.msg.alert(_('error'), _('widget_err_ns'));
        }
        return true;
    }
});
Ext.reg('modx-window-dashboard-widget-place', MODx.window.DashboardWidgetPlace);

/**
 * @class MODx.combo.DashboardWidgets
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-dashboard-widgets
 */
MODx.combo.DashboardWidgets = function(config = {}) {
    Ext.applyIf(config, {
        name: 'widget',
        hiddenName: 'widget',
        displayField: 'name_trans',
        editable: true,
        valueField: 'id',
        fields: [
            'id',
            'name',
            'name_trans',
            'description',
            'description_trans'
        ],
        pageSize: 20,
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Dashboard/Widget/GetList',
            combo: true
        },
        tpl: new Ext.XTemplate(`
            <tpl for=".">
                <div class="x-combo-list-item">
                    <h4 class="modx-combo-title">{name_trans:htmlEncode}</h4>
                    <p class="modx-combo-desc">{description_trans:htmlEncode}</p>
                </div>
            </tpl>
        `)
    });
    MODx.combo.DashboardWidgets.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.DashboardWidgets, MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard-widgets', MODx.combo.DashboardWidgets);
