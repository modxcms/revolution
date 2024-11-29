/**
 * @class MODx.panel.PropertySet
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-property-sets
 */
MODx.panel.PropertySet = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-property-sets',
        cls: 'container',
        items: [{
            html: _('propertysets'),
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('propertysets'),
            layout: 'form',
            id: 'modx-property-set-form',
            border: true,
            items: [{
                html: `<p>${_('propertysets_desc')}</p>`,
                id: 'modx-property-set-msg',
                xtype: 'modx-description'
            }, {
                layout: 'column',
                border: false,
                cls: 'main-wrapper',
                items: [{
                    columnWidth: 0.3,
                    cls: 'left-col',
                    border: false,
                    layout: 'anchor',
                    items: [{
                        xtype: 'modx-tree-property-sets',
                        preventRender: true,
                        anchor: '100%'
                    }]
                }, {
                    columnWidth: 0.7,
                    layout: 'form',
                    border: false,
                    autoHeight: true,
                    id: 'right-column',
                    items: []
                }]
            }]
        }])]
    });
    MODx.panel.PropertySet.superclass.constructor.call(this, config);

    /* load after b/c of safari/ie focus bug */
    (function() {
        Ext.getCmp('right-column').add({
            xtype: 'modx-grid-property-set-properties',
            id: 'modx-grid-element-properties'
        });
    }).defer(50, this);
};
Ext.extend(MODx.panel.PropertySet, MODx.FormPanel);
Ext.reg('modx-panel-property-sets', MODx.panel.PropertySet);

/**
 * @class MODx.grid.PropertySetProperties
 * @extends MODx.grid.ElementProperties
 * @param {Object} config An object of config properties
 * @xtype modx-grid-property-set-properties
 */
MODx.grid.PropertySetProperties = function(config = {}) {
    Ext.applyIf(config, {
        autoHeight: true,
        lockProperties: false,
        tbar: [{
            xtype: 'modx-combo-property-set',
            id: 'modx-combo-property-set',
            baseParams: {
                action: 'Element/PropertySet/GetList',
                combo: true
            },
            listeners: {
                select: {
                    fn: function(cb) {
                        Ext.getCmp('modx-grid-element-properties').changePropertySet(cb);
                    },
                    scope: this
                }
            },
            value: ''
        }, {
            text: _('property_create'),
            id: 'modx-btn-property-create',
            handler: function(btn, e) {
                if (Ext.getCmp('modx-combo-property-set').value !== '') {
                    Ext.getCmp('modx-grid-element-properties').create(btn, e);
                } else {
                    MODx.msg.alert('', _('propertyset_err_ns'));
                }
            },
            scope: this
        }, '->', {
            text: _('propertyset_save'),
            id: 'modx-btn-property-set-save',
            cls: 'primary-button',
            handler: function() { Ext.getCmp('modx-grid-element-properties').save(); },
            scope: this
        }]
    });
    Ext.getCmp('right-column').disable();
    MODx.grid.PropertySetProperties.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.PropertySetProperties, MODx.grid.ElementProperties);
Ext.reg('modx-grid-property-set-properties', MODx.grid.PropertySetProperties);

/**
 * @class MODx.tree.PropertySets
 * @extends MODx.tree.Tree
 * @param {Object} config An object of config properties
 * @xtype modx-tree-property-sets
 */
MODx.tree.PropertySets = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertysets'),
        url: MODx.config.connector_url,
        action: 'Element/PropertySet/GetNodes',
        rootIconCls: 'icon-sitemap',
        root_name: _('propertysets'),
        rootVisible: false,
        enableDD: false,
        tbar: ['->', {
            text: _('propertyset_new'),
            cls: 'primary-button',
            handler: this.createSet,
            hidden: !MODx.perm.new_propertyset || !MODx.perm.save_propertyset,
            scope: this
        }],
        useDefaultToolbar: true
    });
    MODx.tree.PropertySets.superclass.constructor.call(this, config);
    this.on('click', this.loadGrid, this);
};
Ext.extend(MODx.tree.PropertySets, MODx.tree.Tree, {
    loadGrid: function(node, e) {
        const
            [recordType, setId, elId, elType] = node.id.split('_'),
            propsGrid = Ext.getCmp('modx-grid-element-properties'),
            propSetCombo = Ext.getCmp('modx-combo-property-set'),
            setGridData = (response, setId, elId = null, elType = null) => {
                const
                    data = response.object,
                    store = propsGrid.getStore()
                ;
                propsGrid.defaultProperties = data;
                if (elId && elType) {
                    propsGrid.config.elementId = elId;
                    propsGrid.config.elementType = elType;
                } else {
                    delete propsGrid.config.elementId;
                    delete propsGrid.config.elementType;
                }
                store.removeAll();
                store.loadData(data);
                propSetCombo.setValue(setId);
            }
        ;
        Ext.getCmp('right-column').enable();

        if (recordType === 'ps') {
            MODx.Ajax.request({
                url: MODx.config.connector_url,
                params: {
                    action: 'Element/PropertySet/GetProperties',
                    id: setId
                },
                listeners: {
                    success: {
                        fn: response => {
                            setGridData(response, setId);
                        }
                    }
                }
            });
        } else if (recordType === 'el' && elId && elType) {
            MODx.Ajax.request({
                url: MODx.config.connector_url,
                params: {
                    action: 'Element/PropertySet/GetProperties',
                    id: setId,
                    element: elId,
                    element_class: elType
                },
                listeners: {
                    success: {
                        fn: response => {
                            setGridData(response, setId, elId, elType);
                        }
                    }
                }
            });
        }
    },

    createSet: function(btn, e) {
        if (!this.winCreateSet) {
            this.winCreateSet = MODx.load({
                xtype: 'modx-window-property-set-create',
                listeners: {
                    success: {
                        fn: function() {
                            this.refresh();
                            Ext.getCmp('modx-combo-property-set').store.reload();
                        },
                        scope: this
                    }
                }
            });
        }
        this.winCreateSet.reset();
        this.winCreateSet.show(e.target);
    },

    duplicateSet: function(btn, e) {
        const
            [, setId] = this.cm.activeNode.id.split('_'),
            record = this.cm.activeNode.attributes.data
        ;
        record.id = setId;
        record.new_name = _('duplicate_of', { name: record.name });
        if (!this.winDupeSet) {
            this.winDupeSet = MODx.load({
                xtype: 'modx-window-property-set-duplicate',
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.refresh();
                            Ext.getCmp('modx-combo-property-set').store.reload();
                        },
                        scope: this
                    }
                }
            });
        }
        this.winDupeSet.setValues(record);
        this.winDupeSet.show(e.target);
    },

    updateSet: function(btn, e) {
        const
            [, setId] = this.cm.activeNode.id.split('_'),
            record = this.cm.activeNode.attributes.data
        ;
        record.id = setId;
        if (!this.winUpdateSet) {
            this.winUpdateSet = MODx.load({
                xtype: 'modx-window-property-set-update',
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.refresh();
                            Ext.getCmp('modx-combo-property-set').store.reload();
                        },
                        scope: this
                    }
                }
            });
        }
        this.winUpdateSet.setValues(record);
        this.winUpdateSet.show(e.target);
    },

    removeSet: function(btn, e) {
        const [, setId] = this.cm.activeNode.id.split('_');
        MODx.msg.confirm({
            text: _('propertyset_remove_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Element/PropertySet/Remove',
                id: setId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshNode(this.cm.activeNode.id);
                        const propsGrid = Ext.getCmp('modx-grid-element-properties');
                        propsGrid.getStore().removeAll();
                        propsGrid.defaultProperties = [];
                        Ext.getCmp('modx-combo-property-set').setValue('');
                    },
                    scope: this
                }
            }
        });
    },

    addElement: function(btn, e) {
        const
            [, setId] = this.cm.activeNode.id.split('_'),
            record = {
                propertysetName: this.cm.activeNode.text,
                propertyset: setId
            }
        ;
        if (!this.winPSEA) {
            this.winPSEA = MODx.load({
                xtype: 'modx-window-propertyset-element-add',
                record: record,
                listeners: {
                    success: {
                        fn: function() {
                            this.refreshNode(this.cm.activeNode.id, true);
                        },
                        scope: this
                    }
                }
            });
        }
        this.winPSEA.fp.getForm().reset();
        this.winPSEA.fp.getForm().setValues(record);
        this.winPSEA.show(e.target);
    },

    removeElement: function(btn, e) {
        const { attributes } = this.cm.activeNode;
        MODx.msg.confirm({
            text: _('propertyset_element_remove_confirm'),
            url: MODx.config.connector_url,
            params: {
                action: 'Element/PropertySet/RemoveElement',
                element: attributes.pk,
                element_class: attributes.element_class,
                propertyset: attributes.propertyset
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshNode(this.cm.activeNode.id);
                    },
                    scope: this
                }
            }
        });
    }
});
Ext.reg('modx-tree-property-sets', MODx.tree.PropertySets);

/**
 * @class MODx.window.AddElementToPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-propertyset-element-add
 */
MODx.window.AddElementToPropertySet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertyset_element_add'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/PropertySet/AddElement'
        },
        fields: [{
            xtype: 'hidden',
            name: 'propertyset'
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('propertyset'),
            name: 'propertysetName',
            anchor: '100%'
        }, {
            xtype: 'modx-combo-element-class',
            fieldLabel: _('class_name'),
            name: 'element_class',
            id: 'modx-combo-element-class',
            anchor: '100%',
            listeners: {
                select: { fn: this.onClassSelect, scope: this }
            }
        }, {
            xtype: 'modx-combo-elements',
            fieldLabel: _('element'),
            name: 'element',
            id: 'modx-combo-elements',
            anchor: '100%',
            listeners: {
                select: { fn: this.onElementSelect, scope: this }
            }
        }]
    });
    MODx.window.AddElementToPropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.AddElementToPropertySet, MODx.Window, {
    onClassSelect: function(classCombo) {
        const
            elCombo = Ext.getCmp('modx-combo-elements'),
            { store } = elCombo
        ;
        store.baseParams.element_class = classCombo.getValue();
        store.load();
        elCombo.setValue('');
    },
    onElementSelect: function(elCombo) {
        const elType = Ext.getCmp('modx-combo-element-class');
        if (elType.getValue() === '') {
            elType.setValue('MODX\\Revolution\\modSnippet');
        }
    }
});
Ext.reg('modx-window-propertyset-element-add', MODx.window.AddElementToPropertySet);

/**
 * @class MODx.combo.ElementClass
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-element-class
 */
MODx.combo.ElementClass = function(config = {}) {
    Ext.applyIf(config, {
        name: 'element_class',
        hiddenName: 'element_class',
        displayField: 'name',
        valueField: 'name',
        fields: ['name'],
        pageSize: 20,
        editable: false,
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/GetClasses'
        }
    });
    MODx.combo.ElementClass.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.ElementClass, MODx.combo.ComboBox);
Ext.reg('modx-combo-element-class', MODx.combo.ElementClass);

/**
 * @class MODx.combo.Elements
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-elements
 */
MODx.combo.Elements = function(config = {}) {
    Ext.applyIf(config, {
        name: 'element',
        hiddenName: 'element',
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name'],
        pageSize: 20,
        editable: false,
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/GetListByClass',
            element_class: 'MODX\\Revolution\\modSnippet'
        }
    });
    MODx.combo.Elements.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.Elements, MODx.combo.ComboBox);
Ext.reg('modx-combo-elements', MODx.combo.Elements);

/**
 * @class MODx.window.CreatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-create
 */
MODx.window.CreatePropertySet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertyset_create'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/PropertySet/Create'
        },
        autoHeight: true,
        fields: [{
            xtype: 'hidden',
            name: 'id'
        }, {
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            anchor: '100%',
            allowBlank: false,
            maxLength: 50
        }, {
            xtype: 'modx-combo-category',
            fieldLabel: _('category'),
            name: 'category',
            anchor: '100%'
        }, {
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            anchor: '100%',
            grow: true,
            maxLength: 255
        }],
        keys: []
    });
    MODx.window.CreatePropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreatePropertySet, MODx.Window);
Ext.reg('modx-window-property-set-create', MODx.window.CreatePropertySet);

/**
 * @class MODx.window.UpdatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-update
 */
MODx.window.UpdatePropertySet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertyset_update'),
        baseParams: {
            action: 'Element/PropertySet/Update'
        },
        autoHeight: true
    });
    MODx.window.UpdatePropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdatePropertySet, MODx.window.CreatePropertySet);
Ext.reg('modx-window-property-set-update', MODx.window.UpdatePropertySet);

/**
 * @class MODx.window.DuplicatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-duplicate
 */
MODx.window.DuplicatePropertySet = function(config = {}) {
    Ext.applyIf(config, {
        title: _('propertyset_duplicate'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Element/PropertySet/Duplicate'
        },
        autoHeight: true,
        fields: [{
            xtype: 'hidden',
            name: 'id',
            id: 'modx-dpropset-id'
        }, {
            xtype: 'textfield',
            fieldLabel: _('new_name'),
            name: 'name',
            anchor: '100%',
            value: _('duplicate_of', { name: config.record.name }),
            maxLength: 50
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('propertyset_duplicate_copyels'),
            hideLabel: true,
            name: 'copyels',
            id: 'modx-dpropset-copyels',
            checked: true
        }]
    });
    MODx.window.DuplicatePropertySet.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.DuplicatePropertySet, MODx.Window);
Ext.reg('modx-window-property-set-duplicate', MODx.window.DuplicatePropertySet);
