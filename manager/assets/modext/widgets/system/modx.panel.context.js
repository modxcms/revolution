/**
 * @class MODx.panel.Context
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-context
 */
MODx.panel.Context = function(config = {}) {
    Ext.applyIf(config, {
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Context/Get'
        },
        id: 'modx-panel-context',
        cls: 'container',
        class_key: 'modContext',
        plugin: '',
        bodyStyle: '',
        items: [this.getPageHeader(config), MODx.getPageStructure([{
            title: _('general_information'),
            autoHeight: true,
            layout: 'form',
            defaults: { border: false, msgTarget: 'side' },
            items: [{
                xtype: 'modx-description',
                id: 'modx-context-general-desc',
                hidden: true,
                html: ''
            }, {
                xtype: 'panel',
                border: false,
                cls: 'main-wrapper',
                layout: 'form',
                items: [{
                    xtype: 'statictextfield',
                    fieldLabel: _('key'),
                    name: 'key',
                    width: 300,
                    maxLength: 100,
                    enableKeyEvents: true,
                    value: config.context,
                    submitValue: true
                }, {
                    xtype: config.context === 'mgr' ? 'statictextfield' : 'textfield',
                    fieldLabel: _('name'),
                    name: 'name',
                    width: 300,
                    maxLength: 191
                }, {
                    xtype: config.context === 'mgr' ? 'statictextarea' : 'textarea',
                    fieldLabel: _('description'),
                    name: 'description',
                    width: 300,
                    grow: true
                }, {
                    xtype: config.context === 'mgr' ? 'statictextfield' : 'numberfield',
                    fieldLabel: _('rank'),
                    name: 'rank',
                    width: 300
                }, {
                    html: MODx.onContextFormRender,
                    border: false
                }]
            }]
        }, {
            title: _('context_settings'),
            autoHeight: true,
            layout: 'form',
            items: [{
                html: `<p>${_('context_settings_desc')}</p>`,
                id: 'modx-context-settings-desc',
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-context-settings',
                cls: 'main-wrapper',
                title: '',
                preventRender: true,
                context_key: config.context,
                listeners: {
                    afteredit: {
                        fn: function() {
                            this.markDirty();
                        },
                        scope: this
                    }
                }
            }]
        }, {
            title: _('access_permissions'),
            autoHeight: true,
            items: [{
                xtype: 'modx-grid-access-context',
                cls: 'main-wrapper',
                title: '',
                preventRender: true,
                context_key: config.context,
                listeners: {
                    afteredit: { fn: function() { this.markDirty(); }, scope: this }
                }
            }]
        }], {
            id: 'modx-context-tabs'
        })],
        useLoadingMask: true,
        listeners: {
            setup: { fn: this.setup, scope: this },
            success: { fn: this.success, scope: this },
            beforeSubmit: { fn: this.beforeSubmit, scope: this }
        }
    });
    MODx.panel.Context.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Context, MODx.FormPanel, {
    initialized: false,

    setup: function() {
        if (this.initialized || (this.config.context === '' || this.config.context === 0)) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Context/Get',
                key: this.config.context
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const record = response.object;
                        this.config.record = record;
                        if (record.reserved) {
                            record.name = record.name_trans || record.name;
                            record.description = record.description_trans || record.description;
                            if (record.key !== 'web') {
                                const descriptionCmp = Ext.getCmp('modx-context-general-desc');
                                descriptionCmp.update(_('context_reserved_general_desc'));
                                descriptionCmp.show();
                            }
                        }
                        this.getForm().setValues(record);
                        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(record.key));
                        this.fireEvent('ready');
                        MODx.fireEvent('ready');
                        this.initialized = true;
                    },
                    scope: this
                }
            }
        });
    },

    beforeSubmit: function(o) {
        const
            data = {},
            settingsCmp = Ext.getCmp('modx-grid-context-settings')
        ;
        if (settingsCmp) {
            data.settings = settingsCmp.encodeModified();
        }
        Ext.apply(o.form.baseParams, data);
    },

    success: function(o) {
        const
            settingsCmp = Ext.getCmp('modx-grid-context-settings'),
            tree = Ext.getCmp('modx-resource-tree')
        ;
        if (settingsCmp) {
            settingsCmp.getStore().commitChanges();
        }
        if (tree) {
            tree.refresh();
        }
    },

    getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-context-name', [{
            text: _('contexts'),
            href: MODx.getPage('context')
        }]);
    }
});
Ext.reg('modx-panel-context', MODx.panel.Context);
