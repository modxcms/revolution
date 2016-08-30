/**
 * @class MODx.panel.PropertySet
 * @extends MODx.Panel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-property-sets
 */
MODx.panel.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-property-sets'
		,cls: 'container'
        ,items: [{
            html: _('propertysets')
            ,xtype: 'modx-header'
        },{
            layout: 'form'
            ,id: 'modx-property-set-form'
            ,border: true
            ,items: [{
                html: '<p>'+_('propertysets_desc')+'</p>'
                ,id: 'modx-property-set-msg'
                ,xtype: 'modx-description'
            },{
                layout: 'column'
                ,border: false
				,cls: 'main-wrapper'
                ,items: [{
                    columnWidth: .3
                    ,cls: 'left-col'
                    ,border: false
                    ,layout: 'anchor'
                    ,items: [{
                        xtype: 'modx-tree-property-sets'
                        ,preventRender: true
                        ,anchor: '100%'
                    }]
                },{
                    columnWidth: .7
                    ,layout: 'form'
                    ,border: false
                    ,autoHeight: true
                    ,id: 'right-column'
                    ,items: []
                }]
            }]
        }]
    });
    MODx.panel.PropertySet.superclass.constructor.call(this,config);

    /* load after b/c of safari/ie focus bug */
    (function() {
        Ext.getCmp('right-column').add({
            xtype: 'modx-grid-property-set-properties'
            ,id: 'modx-grid-element-properties'
        });
    }).defer(50, this);
};
Ext.extend(MODx.panel.PropertySet,MODx.FormPanel);
Ext.reg('modx-panel-property-sets',MODx.panel.PropertySet);

/**
 * @class MODx.grid.PropertySetProperties
 * @extends MODx.grid.ElementProperties
 * @param {Object} config An object of config properties
 * @xtype modx-grid-property-set-properties
 */
MODx.grid.PropertySetProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,lockProperties: false
        ,tbar: [{
            xtype: 'modx-combo-property-set'
            ,id: 'modx-combo-property-set'
            ,baseParams: {
                action: 'element/propertyset/getList'
            }
            ,listeners: {
                'select': {fn:function(cb) { Ext.getCmp('modx-grid-element-properties').changePropertySet(cb); },scope:this}
            }
            ,value: ''
        },{
            text: _('property_create')
            ,handler: function(btn,e) {
                if (Ext.getCmp('modx-combo-property-set').value != '') {
                    Ext.getCmp('modx-grid-element-properties').create(btn,e);
                } else {
                    MODx.msg.alert('', _('propertyset_err_ns'));
                }
            }
            ,scope: this
        },'->',{
            text: _('propertyset_save')
            ,cls: 'primary-button'
            ,handler: function() { Ext.getCmp('modx-grid-element-properties').save(); }
            ,scope: this
        }]
    });
    MODx.grid.PropertySetProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.PropertySetProperties,MODx.grid.ElementProperties);
Ext.reg('modx-grid-property-set-properties',MODx.grid.PropertySetProperties);

/**
 * @class MODx.tree.PropertySets
 * @extends MODx.tree.Tree
 * @param {Object} config An object of config properties
 * @xtype modx-tree-property-sets
 */
MODx.tree.PropertySets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDD: false
        ,title: ''
        ,url: MODx.config.connector_url
        ,action: 'element/propertyset/getNodes'
        ,tbar: ['->', {
            text: _('propertyset_new')
            ,cls: 'primary-button'
            ,handler: this.createSet
            ,scope: this
        }]
        ,useDefaultToolbar: true
    });
    MODx.tree.PropertySets.superclass.constructor.call(this,config);
    this.on('click',this.loadGrid,this);
};
Ext.extend(MODx.tree.PropertySets,MODx.tree.Tree,{
    loadGrid: function(n,e) {
        var ar = n.id.split('_');
        if (ar[0] == 'ps') {
            MODx.Ajax.request({
                url: MODx.config.connector_url
                ,params: {
                    action: 'element/propertyset/getProperties'
                    ,id: ar[1]
                }
                ,listeners: {
                    'success': {fn:function(r) {
                        var d = r.object;
                        var g = Ext.getCmp('modx-grid-element-properties');
                        var s = g.getStore();
                        g.defaultProperties = d;
                        delete g.config.elementId;
                        delete g.config.elementType;
                        s.removeAll();
                        s.loadData(d);

                        Ext.getCmp('modx-combo-property-set').setValue(ar[1]);
                    },scope:this}
                }
            });
        } else if (ar[0] == 'el' && ar[2] && ar[3]) {
            MODx.Ajax.request({
                url: MODx.config.connector_url
                ,params: {
                    action: 'element/propertyset/getProperties'
                    ,id: ar[1]
                    ,element: ar[2]
                    ,element_class: ar[3]
                }
                ,listeners: {
                    'success': {fn:function(r) {
                        var d = r.object;
                        var g = Ext.getCmp('modx-grid-element-properties');
                        var s = g.getStore();
                        g.defaultProperties = d;
                        g.config.elementId = ar[2];
                        g.config.elementType = ar[3];
                        s.removeAll();
                        s.loadData(d);

                        Ext.getCmp('modx-combo-property-set').setValue(ar[1]);
                    },scope:this}
                }
            });
        }
    }

    ,createSet: function(btn,e) {
        if (!this.winCreateSet) {
            this.winCreateSet = MODx.load({
                xtype: 'modx-window-property-set-create'
                ,listeners: {
                    'success':{fn:function() {
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winCreateSet.reset();
        this.winCreateSet.show(e.target);
    }

    ,duplicateSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        var r = this.cm.activeNode.attributes.data;
        r.id = id[1];
        r.new_name = _('duplicate_of',{name:r.name});
        if (!this.winDupeSet) {
            this.winDupeSet = MODx.load({
                xtype: 'modx-window-property-set-duplicate'
                ,record: r
                ,listeners: {
                    'success':{fn:function() {
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winDupeSet.setValues(r);
        this.winDupeSet.show(e.target);
    }
    ,updateSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        var r = this.cm.activeNode.attributes.data;
        r.id = id[1];
        if (!this.winUpdateSet) {
            this.winUpdateSet = MODx.load({
                xtype: 'modx-window-property-set-update'
                ,record: r
                ,listeners: {
                    'success':{fn:function() {
                        this.refresh();
                        Ext.getCmp('modx-combo-property-set').store.reload();
                    },scope:this}
                }
            });
        }
        this.winUpdateSet.setValues(r);
        this.winUpdateSet.show(e.target);
    }
    ,removeSet: function(btn,e) {
        var id = this.cm.activeNode.id.split('_');
        id = id[1];
        MODx.msg.confirm({
            text: _('propertyset_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'element/propertyset/remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.refreshNode(this.cm.activeNode.id);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    g.getStore().removeAll();
                    g.defaultProperties = [];
                    Ext.getCmp('modx-combo-property-set').setValue('');
                },scope:this}
            }
        });
    }
    ,addElement: function(btn,e) {
        var id = this.cm.activeNode.id.split('_'); id = id[1];
        var t = this.cm.activeNode.text;
        var r = {
            propertysetName: this.cm.activeNode.text
            ,propertyset: id
        };

        if (!this.winPSEA) {
            this.winPSEA = MODx.load({
                xtype: 'modx-window-propertyset-element-add'
                ,record: r
                ,listeners: {
                    'success':{fn:function() { this.refreshNode(this.cm.activeNode.id,true); },scope:this}
                }
            });
        }
        this.winPSEA.fp.getForm().reset();
        this.winPSEA.fp.getForm().setValues(r);
        this.winPSEA.show(e.target);
    }
    ,removeElement: function(btn,e) {
        var d = this.cm.activeNode.attributes;
        MODx.msg.confirm({
            text: _('propertyset_element_remove_confirm')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'element/propertyset/removeElement'
                ,element: d.pk
                ,element_class: d.element_class
                ,propertyset: d.propertyset
            }
            ,listeners: {
                'success': {fn:function() { this.refreshNode(this.cm.activeNode.id); },scope:this}
            }
        });
    }
});
Ext.reg('modx-tree-property-sets',MODx.tree.PropertySets);

/**
 * @class MODx.window.AddElementToPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-propertyset-element-add
 */
MODx.window.AddElementToPropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_element_add')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/propertyset/addElement'
        }
        // ,width: 400
        ,fields: [{
            xtype: 'hidden'
            ,name: 'propertyset'
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('propertyset')
            ,name: 'propertysetName'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-element-class'
            ,fieldLabel: _('class_name')
            ,name: 'element_class'
            ,id: 'modx-combo-element-class'
            ,anchor: '100%'
            ,listeners: {
                'select': {fn:this.onClassSelect,scope:this}
            }
        },{
            xtype: 'modx-combo-elements'
            ,fieldLabel: _('element')
            ,name: 'element'
            ,id: 'modx-combo-elements'
            ,anchor: '100%'
            ,listeners: {
                'select': {fn:this.onElementSelect,scope:this}
            }
        }]
    });
    MODx.window.AddElementToPropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddElementToPropertySet,MODx.Window,{
    onClassSelect: function(cb) {
        var e = Ext.getCmp('modx-combo-elements');
        var s = e.store;
        s.baseParams.element_class = cb.getValue();
        s.load();
        e.setValue('');
    }
    ,onElementSelect: function(cb) {
        var ec = Ext.getCmp('modx-combo-element-class');
        if (ec.getValue() == '') {
            ec.setValue('modSnippet');
        }
    }
});
Ext.reg('modx-window-propertyset-element-add',MODx.window.AddElementToPropertySet);


/**
 * @class MODx.combo.ElementClass
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-element-class
 */
MODx.combo.ElementClass = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'element_class'
        ,hiddenName: 'element_class'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        // ,listWidth: 300
        ,pageSize: 20
        ,editable: false
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/getClasses'
        }
    });
    MODx.combo.ElementClass.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ElementClass,MODx.combo.ComboBox);
Ext.reg('modx-combo-element-class',MODx.combo.ElementClass);

/**
 * @class MODx.combo.Elements
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-elements
 */
MODx.combo.Elements = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'element'
        ,hiddenName: 'element'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        // ,listWidth: 300
        ,pageSize: 20
        ,editable: false
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/getListByClass'
            ,element_class: 'modSnippet'
        }
    });
    MODx.combo.Elements.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Elements,MODx.combo.ComboBox);
Ext.reg('modx-combo-elements',MODx.combo.Elements);

/**
 * @class MODx.window.CreatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-create
 */
MODx.window.CreatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_create')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/propertyset/create'
        }
        ,autoHeight: true
        // ,width: 550
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,anchor: '100%'
            ,allowBlank: false
            ,maxLength: 50
        },{
            xtype: 'modx-combo-category'
            ,fieldLabel: _('category')
            ,name: 'category'
            ,anchor: '100%'
            ,allowBlank: true
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
            ,grow: true
            ,maxLength: 255
        }]
        ,keys: []
    });
    MODx.window.CreatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreatePropertySet,MODx.Window);
Ext.reg('modx-window-property-set-create',MODx.window.CreatePropertySet);


/**
 * @class MODx.window.UpdatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-update
 */
MODx.window.UpdatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_update')
        ,baseParams: {
            action: 'element/propertyset/update'
        }
        ,autoHeight: true
    });
    MODx.window.UpdatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdatePropertySet,MODx.window.CreatePropertySet);
Ext.reg('modx-window-property-set-update',MODx.window.UpdatePropertySet);



/**
 * @class MODx.window.DuplicatePropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-property-set-duplicate
 */
MODx.window.DuplicatePropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_duplicate')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/propertyset/duplicate'
        }
        ,autoHeight: true
        // ,width: 550
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-dpropset-id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('new_name')
            ,name: 'name'
            ,anchor: '100%'
            ,value: _('duplicate_of',{name:config.record.name})
            ,maxLength: 50
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('propertyset_duplicate_copyels')
            // ,labelSeparator: ''
            ,hideLabel: true
            ,name: 'copyels'
            ,id: 'modx-dpropset-copyels'
            ,checked: true
        }]
    });
    MODx.window.DuplicatePropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicatePropertySet,MODx.Window);
Ext.reg('modx-window-property-set-duplicate',MODx.window.DuplicatePropertySet);
