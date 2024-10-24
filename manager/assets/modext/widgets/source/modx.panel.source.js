/**
 * @class MODx.panel.Source
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-source
 */
MODx.panel.Source = function(config = {}) {
    let generalIntro = {};
    if (config.record.reserved) {
        generalIntro = {
            xtype: 'box',
            cls: 'panel-desc',
            html: _('source_reserved_general_desc')
        };
    }
    Ext.applyIf(config, {
        id: 'modx-panel-source',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Source/Update'
        },
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        cls: 'container form-with-labels',
        items: [this.getPageHeader(config), {
            xtype: 'modx-tabs',
            defaults: {
                autoHeight: true,
                border: true,
                bodyCssClass: 'tab-panel-wrapper'
            },
            id: 'modx-source-tabs',
            forceLayout: true,
            deferredRender: false,
            stateful: true,
            stateId: 'modx-source-tabpanel',
            stateEvents: ['tabchange'],
            getState: function() {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            items: [{
                title: _('general_information'),
                layout: 'form',
                id: 'modx-source-form',
                items: [generalIntro, {
                    xtype: 'panel',
                    border: false,
                    cls: 'main-wrapper',
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        layout: 'column',
                        border: false,
                        defaults: {
                            layout: 'form',
                            labelAlign: 'top',
                            labelSeparator: ''
                        },
                        items: [{
                            columnWidth: 0.65,
                            defaults: {
                                anchor: '100%',
                                msgTarget: 'under'
                            },
                            cls: 'main-content',
                            items: [{
                                xtype: 'hidden',
                                name: 'id',
                                id: 'modx-source-id',
                                value: config.record.id
                            }, {
                                xtype: config.record.reserved ? 'statictextfield' : 'textfield',
                                name: 'name',
                                id: 'modx-source-name',
                                fieldLabel: _('name'),
                                description: MODx.expandHelp ? '' : _('source_name_desc'),
                                allowBlank: false,
                                enableKeyEvents: true,
                                listeners: {
                                    keyup: {
                                        scope: this,
                                        fn: function(f, e) {
                                            Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                        }
                                    }
                                }
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('source_name_desc'),
                                cls: 'desc-under'
                            }, {
                                xtype: config.record.reserved ? 'statictextarea' : 'textarea',
                                name: 'description',
                                id: 'modx-source-description',
                                fieldLabel: _('description'),
                                description: MODx.expandHelp ? '' : _('source_description_desc'),
                                grow: true
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('source_description_desc'),
                                cls: 'desc-under'
                            }]
                        }, {
                            columnWidth: 0.35,
                            defaults: {
                                anchor: '100%',
                                msgTarget: 'under'
                            },
                            cls: 'main-content',
                            items: [{
                                disabled: config.record.reserved,
                                xtype: 'modx-combo-source-type',
                                name: 'class_key',
                                hiddenName: 'class_key',
                                id: 'modx-source-type',
                                fieldLabel: _('source_type'),
                                description: MODx.expandHelp ? '' : _('source_type_desc')
                            }, {
                                xtype: 'box',
                                hidden: !MODx.expandHelp,
                                html: _('source_type_desc'),
                                cls: 'desc-under'
                            }]
                        }]
                    }]
                }, {
                    html: `<p>${_('source_properties.intro_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-source-properties',
                    preventRender: true,
                    source: config.record.id,
                    defaultProperties: config.defaultProperties,
                    autoHeight: true,
                    cls: 'main-wrapper',
                    listeners: {
                        afterRemoveRow: { fn: this.markDirty, scope: this }
                    }
                }]
            }, {
                title: _('access'),
                hideMode: 'offsets',
                items: [{
                    html: `<p>${_('source.access.intro_msg')}</p>`,
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-source-access',
                    preventRender: true,
                    source: config.record.id,
                    autoHeight: true,
                    cls: 'main-wrapper',
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
    MODx.panel.Source.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Source, MODx.FormPanel, {
    initialized: false,

    setup: function() {
        if (this.initialized) {
            return false;
        }
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        if (this.config.record.reserved) {
            this.config.record.name = this.config.record.name_trans || this.config.record.name;
            this.config.record.description = this.config.record.description_trans || this.config.record.description;
        }

        this.getForm().setValues(this.config.record);

        /* The component rendering is deferred since we are not using renderTo */
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(this.config.record.name));

        if (!Ext.isEmpty(this.config.record.properties)) {
            const propsGrid = Ext.getCmp('modx-grid-source-properties');
            if (propsGrid) {
                propsGrid.defaultProperties = this.config.defaultProperties;
                propsGrid.getStore().loadData(this.config.record.properties);
            }
        }
        if (!Ext.isEmpty(this.config.record.access)) {
            let { access } = this.config.record;
            const accessGrid = Ext.getCmp('modx-grid-source-access');
            if (accessGrid) {
                access = Ext.decode(access);
                if (!Ext.isEmpty(access)) {
                    accessGrid.defaultProperties = access;
                    accessGrid.getStore().loadData(access);
                }
            }
        }

        this.fireEvent('ready', this.config.record);
        MODx.fireEvent('ready');
        this.initialized = true;
    },

    beforeSubmit: function(o) {
        const
            sourceData = {},
            propsGrid = Ext.getCmp('modx-grid-source-properties'),
            accessGrid = Ext.getCmp('modx-grid-source-access')
        ;
        if (propsGrid) {
            sourceData.properties = propsGrid.encode();
        }
        if (accessGrid) {
            sourceData.access = accessGrid.encode();
        }
        Ext.apply(o.form.baseParams, sourceData);
    },

    success: function(o) {
        if (Ext.isEmpty(this.config.record) || Ext.isEmpty(this.config.record.id)) {
            MODx.loadPage('source/update', `id=${o.result.object.id}`);
        } else {
            const
                propsGrid = Ext.getCmp('modx-grid-source-properties'),
                accessGrid = Ext.getCmp('modx-grid-source-access')
            ;
            Ext.getCmp('modx-abtn-save').setDisabled(false);
            if (propsGrid) {
                propsGrid.getStore().commitChanges();
            }
            if (accessGrid) {
                accessGrid.getStore().commitChanges();
            }
        }
    },

    getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-source-header', [{
            text: _('sources'),
            href: MODx.getPage('source')
        }]);
    }
});
Ext.reg('modx-panel-source', MODx.panel.Source);
