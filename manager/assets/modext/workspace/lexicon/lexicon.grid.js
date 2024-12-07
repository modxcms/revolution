/**
 * Loads a grid for managing lexicons.
 *
 * @class MODx.grid.Lexicon
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-lexicon
 */
MODx.grid.Lexicon = function(config = {}) {
    this.languageFilterValue = MODx.util.url.getParamValue('language') || this.currentLanguage;
    this.topicFilterValue = MODx.util.url.getParamValue('topic') || 'default';
    this.namespaceFilterValue = MODx.util.url.getParamValue('ns') || 'core';

    Ext.applyIf(config, {
        id: 'modx-grid-lexicon',
        url: MODx.config.connector_url,
        fields: [
            'name',
            'value',
            'namespace',
            'topic',
            'language',
            'editedon',
            'overridden'
        ],
        baseParams: {
            action: 'Workspace/Lexicon/GetList',
            namespace: this.namespaceFilterValue,
            topic: this.topicFilterValue,
            language: this.languageFilterValue
        },
        paging: true,
        autosave: true,
        preventSaveRefresh: false,
        save_action: 'Workspace/Lexicon/UpdateFromGrid',
        columns: [{
            header: _('name'),
            dataIndex: 'name',
            width: 200,
            sortable: true,
            renderer: this._renderStatus
        }, {
            header: _('value'),
            dataIndex: 'value',
            width: 500,
            sortable: false,
            editor: {
                xtype: 'textarea'
            },
            renderer: this._renderStatus
        }, {
            header: _('last_modified'),
            dataIndex: 'editedon',
            width: 125,
            renderer: this._renderLastModDate
        }],
        tbar: {
            cls: 'has-nested-filters',
            items: [
                this.getCreateButton('lexicon', 'createEntry'),
                {
                    text: _('lexicon_revert'),
                    handler: this.reloadFromBase,
                    scope: this,
                    listeners: {
                        render: {
                            fn: function(btn) {
                                if (!this.userCanEdit) {
                                    btn.hide();
                                }
                            },
                            scope: this
                        }
                    }
                },
                '->',
                {
                    xtype: 'container',
                    layout: 'form',
                    itemId: 'filter-namespace-container',
                    cls: 'grid-filter',
                    width: 150,
                    defaults: {
                        anchor: '100%'
                    },
                    items: [
                        {
                            xtype: 'label',
                            html: _('namespace')
                        }, {
                            xtype: 'modx-combo-namespace',
                            itemId: 'filter-namespace',
                            hideLabel: true,
                            submitValue: false,
                            value: this.namespaceFilterValue,
                            baseParams: {
                                action: 'Workspace/PackageNamespace/GetList',
                                language: this.languageFilterValue,
                                topic: this.topicFilterValue,
                                isGridFilter: true,
                                targetGrid: 'MODx.grid.Lexicon'
                            },
                            listeners: {
                                select: {
                                    fn: function(cmp, record, selectedIndex) {
                                        this.updateDependentFilter('filter-language', 'namespace', record.data.name);
                                        this.updateDependentFilter('filter-topic', 'namespace', record.data.name);
                                        this.applyGridFilter(cmp, 'ns');
                                    },
                                    scope: this
                                },
                                change: {
                                    // Support typed-in value (where the select event is not triggered)
                                    fn: function(cmp, newValue, oldValue) {
                                        this.updateDependentFilter('filter-language', 'namespace', newValue);
                                        this.updateDependentFilter('filter-topic', 'namespace', newValue);
                                        this.applyGridFilter(cmp, 'ns');
                                    },
                                    scope: this
                                }
                            }
                        }
                    ]
                },
                {
                    xtype: 'container',
                    layout: 'form',
                    itemId: 'filter-language-container',
                    cls: 'grid-filter',
                    width: 100,
                    defaults: {
                        anchor: '100%'
                    },
                    items: [
                        {
                            xtype: 'label',
                            html: _('language')
                        }, {
                            xtype: 'modx-combo-language',
                            itemId: 'filter-language',
                            hideLabel: true,
                            submitValue: false,
                            queryParam: 'query',
                            value: this.languageFilterValue,
                            baseParams: {
                                action: 'System/Language/GetList',
                                namespace: this.namespaceFilterValue,
                                topic: this.topicFilterValue,
                                isGridFilter: true,
                                targetGrid: 'MODx.grid.Lexicon'
                            },
                            listeners: {
                                select: {
                                    fn: function(cmp, record, selectedIndex) {
                                        this.updateDependentFilter('filter-topic', 'language', record.data.name);
                                        this.updateDependentFilter('filter-namespace', 'language', record.data.name);
                                        this.applyGridFilter(cmp, 'language');
                                    },
                                    scope: this
                                },
                                change: {
                                    // Support typed-in value (where the select event is not triggered)
                                    fn: function(cmp, newValue, oldValue) {
                                        this.updateDependentFilter('filter-topic', 'language', newValue);
                                        this.updateDependentFilter('filter-namespace', 'language', newValue);
                                        this.applyGridFilter(cmp, 'language');
                                    },
                                    scope: this
                                }
                            }
                        }
                    ]
                },
                {
                    xtype: 'container',
                    layout: 'form',
                    itemId: 'filter-topic-container',
                    cls: 'grid-filter',
                    width: 150,
                    defaults: {
                        anchor: '100%'
                    },
                    items: [
                        {
                            xtype: 'label',
                            html: _('topic')
                        }, {
                            xtype: 'modx-combo-lexicon-topic',
                            itemId: 'filter-topic',
                            hideLabel: true,
                            submitValue: false,
                            queryParam: 'query',
                            value: this.topicFilterValue,
                            baseParams: {
                                action: 'Workspace/Lexicon/Topic/GetList',
                                namespace: this.namespaceFilterValue,
                                language: this.languageFilterValue,
                                isGridFilter: true,
                                targetGrid: 'MODx.grid.Lexicon'
                            },
                            listeners: {
                                select: {
                                    fn: function(cmp, record, selectedIndex) {
                                        this.updateDependentFilter('filter-namespace', 'topic', record.data.name);
                                        this.updateDependentFilter('filter-language', 'topic', record.data.name);
                                        this.applyGridFilter(cmp, 'topic');
                                    },
                                    scope: this
                                },
                                change: {
                                    // Support typed-in value (where the select event is not triggered)
                                    fn: function(cmp, newValue, oldValue) {
                                        this.updateDependentFilter('filter-namespace', 'topic', newValue);
                                        this.updateDependentFilter('filter-language', 'topic', newValue);
                                        this.applyGridFilter(cmp, 'topic');
                                    },
                                    scope: this
                                }
                            }
                        }
                    ]
                },
                this.getQueryFilterField(),
                this.getClearFiltersButton(`filter-namespace:core, filter-topic:default, filter-language:${this.currentLanguage}, filter-query`)
            ]
        }
    });
    MODx.grid.Lexicon.superclass.constructor.call(this, config);

    this.gridMenuActions = ['edit'];

    // Note there are currently no action-specific permissions for Lexicons
    this.setUserCanCreate(['lexicons']);
    this.setUserCanEdit(['lexicons']);
    this.setUserCanDelete(['lexicons']);
    this.setShowActionsMenu();
};
Ext.extend(MODx.grid.Lexicon, MODx.grid.Grid, {
    console: null,

    _renderStatus: function(value, metaData, record, rowIndex) {
        switch (record.data.overridden) {
            case 1:
                return `<span style="color: green;">${value}</span>`;
            case 2:
                return `<span style="color: purple;">${value}</span>`;
            default:
                return `<span>${value}</span>`;
        }
    },

    /**
     * @deprecated since 3.0.5. To be removed in future release. Datetime formatting
     * now handled in back end processors to provide uniform display across components.
     */
    _renderLastModDate: function(value) {
        return value;
    },

    loadWindow2: function(btn, e, o) {
        this.menu.record = {
            namespace: this.getFilterComponent('filter-namespace').getValue(),
            language: this.getFilterComponent('filter-language').getValue()
        };
        if (o.xtype !== 'modx-window-lexicon-import') {
            this.menu.record.topic = this.getFilterComponent('filter-topic').getValue();
        }
        this.loadWindow(btn, e, o);
    },

    reloadFromBase: function() {
        const
            namespace = this.getFilterComponent('filter-namespace').getValue(),
            topic = this.getFilterComponent('filter-topic').getValue(),
            language = this.getFilterComponent('filter-language').getValue(),
            registryTopic = '/workspace/lexicon/reload/'
        ;

        MODx.msg.confirm({
            text: _('lexicon_revert_confirm', {
                namespace: namespace,
                topic: topic,
                language: language
            }),
            url: this.config.url,
            params: {
                action: 'Workspace/Lexicon/ReloadFromBase',
                register: 'mgr',
                topic: registryTopic,
                namespace: namespace,
                lexiconTopic: topic,
                language: language
            },
            listeners: {
                success: {
                    fn: function() {
                        this.console = MODx.load({
                            xtype: 'modx-console',
                            register: 'mgr',
                            topic: registryTopic
                        });

                        this.console.on('complete', function() {
                            this.refresh();
                        }, this);
                        this.console.show(Ext.getBody());
                    },
                    scope: this
                }
            }
        });
    },

    revertEntry: function() {
        const { record } = this.menu;
        record.action = 'Workspace/Lexicon/Revert';

        MODx.Ajax.request({
            url: this.config.url,
            params: record,
            listeners: {
                success: {
                    fn: function(r) {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },

    getMenu: function() {
        const
            record = this.getSelectionModel().getSelected(),
            menu = []
        ;
        if (record.data.overridden) {
            menu.push({
                text: _('entry_revert'),
                handler: this.revertEntry
            });
        }
        return menu;
    },

    createEntry: function(btn, e) {
        const record = this.menu.record || {};

        record.namespace = this.getFilterComponent('filter-namespace').getValue();
        record.language = this.getFilterComponent('filter-language').getValue();
        record.topic = this.getFilterComponent('filter-topic').getValue();

        if (!this.createEntryWindow) {
            this.createEntryWindow = MODx.load({
                xtype: 'modx-window-lexicon-entry-create',
                record: record,
                listeners: {
                    success: {
                        fn: function(o) {
                            this.refresh();
                        },
                        scope: this
                    }
                }
            });
        }
        this.createEntryWindow.reset();
        this.createEntryWindow.setValues(record);
        this.createEntryWindow.show(e.target);
    }
});
Ext.reg('modx-grid-lexicon', MODx.grid.Lexicon);

MODx.window.LexiconEntryCreate = function(config = {}) {
    this.ident = config.ident || `lexentc${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Workspace/Lexicon/Create',
        fileUpload: true,
        formDefaults: {
            anchor: '100%',
            msgTarget: 'under',
            allowBlank: false
        },
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('name'),
            itemId: 'name',
            name: 'name'
        }, {
            xtype: 'modx-combo-namespace',
            fieldLabel: _('namespace'),
            name: 'namespace',
            itemId: 'namespace'
        }, {
            xtype: 'modx-combo-lexicon-topic',
            fieldLabel: _('topic'),
            name: 'topic',
            itemId: 'topic'
        }, {
            xtype: 'modx-combo-language',
            fieldLabel: _('language'),
            name: 'language',
            itemId: 'language'
        }, {
            xtype: 'textarea',
            fieldLabel: _('value'),
            itemId: 'value',
            name: 'value',
            allowBlank: true
        }]
    });
    MODx.window.LexiconEntryCreate.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.LexiconEntryCreate, MODx.Window);
Ext.reg('modx-window-lexicon-entry-create', MODx.window.LexiconEntryCreate);
