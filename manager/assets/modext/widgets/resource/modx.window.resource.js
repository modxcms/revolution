MODx.window.CreateResource = function(config = {}) {
    this.ident = config.ident || `tplpick${Ext.id()}`;
    const id = this.ident,
          requireAlias = parseInt(MODx.config.friendly_urls, 10) && !parseInt(MODx.config.automatic_alias, 10),
          aliasLength = parseInt(MODx.config.friendly_alias_max_length, 10) || 0,
          resourceDetail = [
              {
                  columnWidth: requireAlias ? 0.33 : 0.5,
                  items: [
                      {
                          xtype: 'modx-combo-class-derivatives',
                          fieldLabel: _('resource_type'),
                          description: MODx.expandHelp ? '' : _('resource_type_help'),
                          name: 'class_key',
                          hiddenName: 'class_key',
                          anchor: '100%',
                          allowBlank: false,
                          value: config.record.class_key || 'MODX\\Revolution\\modDocument',
                      }
                  ]
              }, {
                  columnWidth: requireAlias ? 0.33 : 0.5,
                  items: [
                      {
                          xtype: 'modx-field-parent-change',
                          fieldLabel: _('resource_parent'),
                          description: `<b>[[*parent]]</b><br>${_('resource_parent_help')}`,
                          name: 'parent-cmb',
                          id: `modx-${id}-parent-change`,
                          value: config.record.parent || 0,
                          anchor: '100%',
                          parentcmp: 'modx-template-picker-parent-id',
                          contextcmp: 'modx-template-picker-parent-context',
                          currentid: id
                      }
                  ]
              }
          ]
    ;
    if (requireAlias) {
        resourceDetail.push({
            columnWidth: 0.34,
            defaults: {
                anchor: '100%',
                enableKeyEvents: true,
                validationEvent: 'change',
                validateOnBlur: false,
                msgTarget: 'under'
            },
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: _('resource_alias'),
                    name: 'alias',
                    allowBlank: false,
                    maxLength: (aliasLength > 191 || aliasLength === 0) ? 191 : aliasLength,
                    value: config.record.alias || '',
                    listeners: {
                        afterrender: function() {
                            this.clearInvalid();
                        }
                    }
                }
            ]
        });
    }
    Ext.applyIf(config, {
        autoHeight: true,
        title: _('document_new'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Resource/Create'
        },
        width: 600,
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('resource_pagetitle'),
            description: MODx.expandHelp ? '' : _('resource_pagetitle_help'),
            name: 'pagetitle',
            maxLength: 191,
            anchor: '100%',
            allowBlank: false,
            validationEvent: 'change',
            validateOnBlur: false,
            value: config.record.pagetitle || '',
            listeners: {
                afterrender: function() {
                    this.clearInvalid();
                }
            }
        }, {
            xtype: 'hidden',
            name: 'parent',
            id: 'modx-template-picker-parent-id'
        }, {
            xtype: 'hidden',
            name: 'context_key',
            id: 'modx-template-picker-parent-context'
        }, {
            layout: 'column',
            defaults: {
                layout: 'form',
                labelSeparator: ''
            },
            items: resourceDetail
        }, {
            xtype: 'modx-panel-template-picker',
            fieldLabel: _('resource_template'),
            description: MODx.expandHelp ? '' : _('resource_template_help'),
            name: 'template',
            value: config.record.template || MODx.config.default_template
        }]
    });
    MODx.window.CreateResource.superclass.constructor.call(this, config);
};

Ext.extend(MODx.window.CreateResource, MODx.Window);

Ext.reg('modx-window-create-resource', MODx.window.CreateResource);

MODx.panel.TemplatePicker = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'panel',
        layout: 'form',
        labelSeparator: '',
        items: [{
            layout: 'column',
            defaults: {
                layout: 'form',
                labelSeparator: ''
            },
            items: [{
                columnWidth: 0.4,
                items: [{
                    xtype: 'modx-combo-template-picker',
                    id: 'modx-resource-template-picker',
                    name: 'template',
                    value: config.record || MODx.config.default_template,
                    listeners: {
                        select: {
                            fn: this.setPreview,
                            scope: this
                        }
                    }
                }]
            }, {
                columnWidth: 0.6,
                items: [{
                    xtype: 'modx-panel-template-preview',
                    id: 'modx-resource-template-preview'
                }]
            }]
        }]
    });

    MODx.panel.TemplatePicker.superclass.constructor.call(this, config);
};

Ext.extend(MODx.panel.TemplatePicker, Ext.Panel, {
    setPreview: function(record) {
        Ext.getCmp('modx-resource-template-preview').setPreview(record);
    }
});

Ext.reg('modx-panel-template-picker', MODx.panel.TemplatePicker);

MODx.combo.TemplatePicker = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'x-form-template-picker',
        layout: 'form',
        defaults: {
            hideLabel: true,
        },
        items: [{
            xtype: 'textfield',
            itemCls: 'x-form-template-picker-search',
            cls: 'x-form-field-search',
            anchor: '100%',
            emptyText: _('search'),
            listeners: {
                'keyup': {
                    fn: this.filterItems,
                    scope: this
                }
            },
            enableKeyEvents: true
        }],
        store: new Ext.data.JsonStore({
            url: MODx.config.connector_url,
            baseParams: {
                action: 'Element/Template/GetList',
                combo: true,
                limit: 0
            },
            root: 'results',
            totalProperty: 'total',
            fields: ['id', 'templatename', 'description', 'category_name', 'preview'],
            errorReader: MODx.util.JSONReader,
            autoDestroy: true,
            autoLoad: true,
            listeners: {
                'load': {
                    fn: this.loadItems,
                    scope: this
                },
                'loadexception': {fn: function(o,trans,resp) {
                    var status = _('code') + ': ' + resp.status + ' ' + resp.statusText + '<br/>';
                    MODx.msg.alert(_('error'), status + resp.responseText);
                }}
            }
        })
    });

    MODx.combo.TemplatePicker.superclass.constructor.call(this, config);
};

Ext.extend(MODx.combo.TemplatePicker, Ext.Panel, {
    loadItems: function(store, data) {
        var value = this.value;

        var items = [];
        var category = '';

        Ext.each(data, function(record) {
            if (category !== record.data.category_name) {
                if (!Ext.isEmpty(record.data.category_name)) {
                    items.push({
                        hideLabel: true,
                        boxLabel: record.data.category_name,
                        disabled: true,
                        itemCls: 'x-form-template-picker-category'
                    });
                }
            }

            items.push({
                hideLabel: true,
                boxLabel: record.data.templatename,
                name: this.name || 'template',
                inputValue: record.data.id,
                itemCls: 'x-form-template-picker-item',
                record: record,
                checked: record.data.id == value
            });

            category = record.data.category_name;
        }, this);

        this.add({
            xtype: 'radiogroup',
            id: 'modx-template-picker-templates',
            itemCls: 'x-form-template-picker-templates',
            columns: 1,
            items: items,
            listeners: {
                render: {
                    fn: function(cmp) {
                        const value = cmp.getValue(),
                              record = value?.record
                        ;
                        if (record) {
                            this.fireEvent('select', record);
                        }
                    },
                    scope: this
                },
                change: {
                    fn: function(cmp, checked) {
                        if (checked.record) {
                            this.fireEvent('select', checked.record);
                        }
                    },
                    scope: this
                }
            }
        });

        this.doLayout();
    },
    filterItems: function(tf) {
        if (undefined !== (panel = Ext.getCmp('modx-template-picker-templates'))) {
            panel.items.each(function(object) {
                if (!Ext.isEmpty(tf.getValue()) && object.record) {
                    var regex = new RegExp(tf.getValue(), 'i');

                    if (-1 === object.record.data.templatename.search(regex)) {
                        object.hide();
                    } else {
                        object.show();
                    }
                } else {
                    object.show();
                }
            });
        }
    }
});

Ext.reg('modx-combo-template-picker', MODx.combo.TemplatePicker);

MODx.panel.TemplatePreview = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'x-form-template-preview'
    });

    MODx.panel.TemplatePreview.superclass.constructor.call(this, config);
};

Ext.extend(MODx.panel.TemplatePreview, Ext.Panel, {
    setPreview: function(record) {
        this.removeAll();
        if ('' == record.data.preview || undefined === record.data.preview) {
            this.addClass('x-form-template-preview-empty');

            var html = '';
        } else {
            this.removeClass('x-form-template-preview-empty');

            var html = '<img src="' + record.data.preview + '" alt="' + record.data.templatename + '" />';
        }

        this.add({
            xtype: 'box',
            autoEl: {
                tag: 'div',
                cls: 'x-form-template-preview-image',
                html: html
            },
            hidden: '' == record.data.image ? true : false
        }, {
            xtype: 'box',
            autoEl: {
                tag: 'div',
                cls: 'x-form-template-preview-desc',
                html: '<p>' + record.data.description + '</p>'
            },
            hidden: '' == record.data.description ? true : false
        });

        this.doLayout();
    }
});

Ext.reg('modx-panel-template-preview', MODx.panel.TemplatePreview);
