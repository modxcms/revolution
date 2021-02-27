MODx.grid.SourceProperties = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="modx-property-description"><i>{desc_trans}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('properties')
        ,id: 'modx-grid-source-properties'
        ,maxHeight: 300
        ,fields: ['name','desc','xtype','options','value','lexicon','overridden','desc_trans']
        ,autoExpandColumn: 'value'
        ,sortBy: 'name'
        ,width: '100%'
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,loadMask: true
        ,lockProperties: true
        ,panel: 'modx-panel-source'
        ,plugins: [this.exp]
        ,columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,renderer: this._renderName
        },{
            header: _('type')
            ,dataIndex: 'xtype'
            ,width: 100
            ,renderer: this._renderType
            ,sortable: true
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,id: 'value'
            ,width: 250
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
            ,sortable: true
        }]
        ,tbar: [{
            text: _('create')
            ,id: 'modx-btn-property-create'
            ,cls: 'primary-button'
            ,handler: this.create
            ,scope: this
        },{
            text: _('property_revert_all')
            ,id: 'modx-btn-property-revert-all'
            ,handler: this.revertAll
            ,scope:this
        }]
        ,collapseFirst: false
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });
    MODx.grid.SourceProperties.superclass.constructor.call(this,config);
    this.on('afteredit', this.propertyChanged, this);
    this.on('afterRemoveRow', this.propertyChanged, this);
    this.on('celldblclick',this.onDirty,this);
    this.on('render',function() {
        this.mask = new Ext.LoadMask(this.getEl());
    },this);
};
Ext.extend(MODx.grid.SourceProperties,MODx.grid.LocalProperty,{
    defaultProperties: []

    ,onDirty: function() {
        if (this.config.panel) {
            Ext.getCmp(this.config.panel).fireEvent('fieldChange');
        }
    }

    ,_renderType: function(v,md,rec,ri) {
        switch (v) {
            case 'combo-boolean': return _('yesno'); break;
            case 'datefield': return _('date'); break;
            case 'numberfield': return _('integer'); break;
        }
        return _(v);
    }
    ,_renderName: function(v,md,rec,ri) {
        switch (rec.data.overridden) {
            case 1:
                return '<span style="color: green;">'+v+'</span>'; break;
            case 2:
                return '<span style="color: purple;">'+v+'</span>';
            default:
                return '<span>'+v+'</span>';
        }
    }


    ,create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-source-property-create'
            ,blankValues: true
            ,listeners: {
                'success': {fn:function(r) {

                    var rec = new this.propRecord({
                        name: r.name
                        ,desc: r.desc
                        ,desc_trans: r.desc
                        ,xtype: r.xtype
                        ,options: r.options
                        ,value: r.value
                        ,lexicon: r.lexicon
                        ,overridden: 2
                    });
                    this.getStore().add(rec);
                    this.propertyChanged();
                    this.onDirty();
                },scope:this}
            }
        });
    }

    ,update: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-source-property-update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var ri = this.menu.recordIndex;
                    var d = this.defaultProperties[ri];
                    d = (d && d[4]) ? d[4] : r.value;
                    var rec = s.getAt(this.menu.recordIndex);
                    rec.set('name',r.name);
                    rec.set('desc',r.desc);
                    rec.set('desc_trans', r.desc);
                    rec.set('xtype',r.xtype);
                    rec.set('options',r.options);
                    rec.set('value',r.value);
                    rec.set('lexicon',r.lexicon);
                    rec.set('overridden',r.overridden == 2 ? 2 : (d.toString() == r.value.toString() ? 0 : 1));
                    this.getView().refresh();
                    this.onDirty();
                },scope:this}
            }
        });
    }

    ,revert: function(btn,e) {
        Ext.Msg.confirm(_('warning'),_('property_revert_confirm'),function(e) {
            if (e == 'yes') {
                var ri = this.menu.recordIndex;
                var d = this.defaultProperties[ri];
                if (d) {
                    var rec = this.getStore().getAt(ri);
                    rec.set('name',d[0]);
                    rec.set('desc',d[1]);
                    rec.set('xtype',d[2]);
                    rec.set('options',d[3]);
                    rec.set('value',d[4]);
                    rec.set('overridden',0);
                    rec.commit();
                }
            }
        },this);
    }

    ,revertAll: function(btn,e) {
        Ext.Msg.confirm(_('warning'),_('property_revert_all_confirm'),function(e) {
            if (e == 'yes') {
                this.getStore().loadData(this.defaultProperties);
            }
        },this);
    }

    ,removeMultiple: function(btn,e) {
        var rows = this.getSelectionModel().getSelections();
        var rids = [];
        for (var i=0;i<rows.length;i=i+1) {
            rids.push(rows[i].data.id);
        }
        Ext.Msg.confirm(_('warning'),_('properties_remove_confirm'),function(e) {
            if (e == 'yes') {
                for (var f=0;f<rows.length;f=f+1) {
                    this.store.remove(rows[f]);
                }
            }
        },this);
    }

    ,_showMenu: function(g,ri,e) {
        var sm = this.getSelectionModel();
        if (sm.getSelections().length > 1) {
            e.stopEvent();
            e.preventDefault();
            this.menu.removeAll();
            this.addContextMenuItem([{
                text: _('delete')
                ,handler: this.removeMultiple
                ,scope: this
            }]);
            this.menu.show(e.target);
        } else {
            MODx.grid.SourceProperties.superclass._showMenu.call(this,g,ri,e);
        }
    }


    ,getMenu: function() {
        var def = false;

        var r = this.menu.record;
        var m = []
        m.push({
            text: _('edit')
            ,scope: this
            ,handler: this.update
        });

        if (r.overridden) {
            m.push({
                text: _('property_revert')
                ,scope: this
                ,handler: this.revert
            });
        }
        if (r.overridden != 1) {
            m.push({
                text: _('delete')
                ,scope: this
                ,handler: this.remove.createDelegate(this,[{
                    title: _('warning')
                    ,text: _('property_remove_confirm')
                }])
            });
        }
        return m;
    }

    ,propertyChanged: function() {
        var ep = Ext.getCmp(this.config.panel);
        if (!ep) return false;
        var hf = ep.getForm().findField((this.config.hiddenPropField || 'props'));
        if (hf) {
            hf.setValue('1');
            ep.fireEvent('fieldChange',{
                field: hf
                ,form: ep.getForm()
            });
        }
        return true;
    }

});
Ext.reg('modx-grid-source-properties',MODx.grid.SourceProperties);



MODx.grid.SourcePropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_options')
        ,id: 'modx-grid-source-property-options'
        ,autoHeight: true
        ,maxHeight: 300
        ,width: '100%'
        ,fields: ['text','value','name']
        ,data: []
        ,columns: [{
            header: _('name')
            ,dataIndex: 'text'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,id: 'value'
            ,width: 250
            ,editor: { xtype: 'textfield' ,allowBlank: true }
        }]
        ,tbar: [{
            text: _('create')
            ,cls: 'primary-button'
            ,handler: this.create
            ,scope: this
        }]
    });
    MODx.grid.SourcePropertyOption.superclass.constructor.call(this,config);
    this.optRecord = Ext.data.Record.create([{name: 'text'},{name: 'value'}]);
};
Ext.extend(MODx.grid.SourcePropertyOption,MODx.grid.LocalGrid,{
    create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-source-property-option-create'
            ,listeners: {
                'success': {fn:function(r) {
                    var rec = new this.optRecord({
                        text: r.text
                        ,value: r.value
                    });
                    this.getStore().add(rec);
                },scope:this}
            }
        });
    }

    ,getMenu: function() {
        return [{
            text: _('delete')
            ,scope: this
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('property_option_remove_confirm')
            }])
        }];
    }
});
Ext.reg('modx-grid-source-property-options',MODx.grid.SourcePropertyOption);

/**
 * @class MODx.window.CreateSourceProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-source-property-create
 */
MODx.window.CreateSourceProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,id: 'modx-window-source-property-create'
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cep-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            fieldLabel: _('description')
            ,name: 'desc'
            ,id: 'modx-cep-desc'
            ,xtype: 'textarea'
            ,anchor: '100%'
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,id: 'modx-cep-xtype'
            ,xtype: 'modx-combo-xtype'
            ,anchor: '100%'
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('modx-cep-grid-source-property-options');
                    if (!g) return;
                    if (cb.getValue() == 'list') {
                       g.show();
                    } else {
                       g.hide();
                    }
                    this.syncSize();
                },scope:this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('lexicon')
            ,name: 'lexicon'
            ,id: 'modx-cep-lexicon'
            ,anchor: '100%'
            ,allowBlank: true
        },{
            xtype: 'modx-source-value-field'
            ,xtypeField: 'modx-cep-xtype'
            ,id: 'modx-cep-value'
            ,anchor: '100%'
        },{
            xtype: 'modx-grid-source-property-options'
            ,id: 'modx-cep-grid-source-property-options'
        }]
    });
    MODx.window.CreateSourceProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.CreateSourceProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        var g = Ext.getCmp('modx-cep-grid-source-property-options');
        var opt = eval(g.encode());
        Ext.apply(v,{
            options: opt
        });

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
    ,onShow: function() {
        var g = Ext.getCmp('modx-cep-grid-source-property-options');
        g.getStore().removeAll();
        g.hide();
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-source-property-create',MODx.window.CreateSourceProperty);



/**
 * @class MODx.window.UpdateSourceProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-source-property-update
 */
MODx.window.UpdateSourceProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('edit')
        ,id: 'modx-window-source-property-update'
        ,saveBtnText: _('done')
        ,forceLayout: true
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-uep-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('description')
            ,name: 'desc'
            ,id: 'modx-uep-desc'
            ,xtype: 'textarea'
            ,anchor: '100%'
        },{
            fieldLabel: _('type')
            ,name: 'xtype'
            ,xtype: 'modx-combo-xtype'
            ,id: 'modx-uep-xtype'
            ,anchor: '100%'
            ,listeners: {
                'select': {fn:function(cb,r,i) {
                    var g = Ext.getCmp('modx-uep-grid-source-property-options');
                    if (!g) return;
                    var v = cb.getValue();
                    if (v == 'list') {
                        g.show();
                    } else {
                        g.hide();
                    }
                    this.syncSize();
                },scope:this}
            }
        },{
            xtype: 'textfield'
            ,fieldLabel: _('lexicon')
            ,name: 'lexicon'
            ,id: 'modx-uep-lexicon'
            ,anchor: '100%'
            ,allowBlank: true
        },{
            xtype: 'hidden'
            ,name: 'overridden'
            ,id: 'modx-uep-overridden'
        },{
            xtype: 'modx-source-value-field'
            ,xtypeField: 'modx-uep-xtype'
            ,name: 'value'
            ,anchor: '100%'
            ,id: 'modx-uep-value'
        },{
            id: 'modx-uep-grid-source-property-options'
            ,xtype: 'modx-grid-source-property-options'
            ,autoHeight: true
        }]
    });
    MODx.window.UpdateSourceProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.UpdateSourceProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        var g = Ext.getCmp('modx-uep-grid-source-property-options');
        var opt = eval(g.encode());
        Ext.apply(v,{
            options: opt
        });

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',v)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
    ,onShow: function() {
        var g = Ext.getCmp('modx-uep-grid-source-property-options');
        if (!g) return;
        if (this.fp.getForm().findField('xtype').getValue() == 'list') {
            g.show();
        } else {
            g.hide();
        }
        g.getStore().removeAll();
        var gp = Ext.getCmp('modx-grid-source-properties');
        var rec = gp.getSelectionModel().getSelected();
        if (rec) {
            var opt = rec.data.options;
            var opts = [];
            for (var x in opt) {
              if (opt.hasOwnProperty(x)) {
                opts.push([opt[x].text,opt[x].value]);
              }
            }
            g.getStore().loadData(opts);
        }
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-source-property-update',MODx.window.UpdateSourceProperty);

/**
 * @class MODx.window.CreateSourcePropertyOption
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-source-property-option-create
 */
MODx.window.CreateSourcePropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,id: 'modx-window-source-property-option-create'
        ,saveBtnText: _('done')
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'text'
            ,id: 'modx-cepo-text'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-cepo-value'
            ,xtype: 'textfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.CreateSourcePropertyOption.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateSourcePropertyOption,MODx.Window,{
    submit: function() {
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',this.fp.getForm().getValues())) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('modx-window-source-property-option-create',MODx.window.CreateSourcePropertyOption);



/**
 * Displays a xtype combobox
 *
 * @class MODx.combo.xType
 * @extends Ext.form.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-xtype
 */
MODx.combo.xType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                [_('textfield'),'textfield']
                ,[_('textarea'),'textarea']
                ,[_('yesno'),'combo-boolean']
                ,[_('date'),'datefield']
                ,[_('list'),'list']
                ,[_('integer'),'numberfield']
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,name: 'xtype'
        ,hiddenName: 'xtype'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.xType,Ext.form.ComboBox);
Ext.reg('modx-combo-xtype',MODx.combo.xType);




MODx.form.SourceValueField = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        fieldLabel: _('value')
        ,name: 'value'
        ,xtype: 'textfield'
        ,anchor: '100%'
    });
    MODx.form.SourceValueField.superclass.constructor.call(this,config);
    this.config = config;
    this.on('change',this.checkValue,this);
};
Ext.extend(MODx.form.SourceValueField,Ext.form.TextField,{
    checkValue: function(fld,nv,ov) {
        var t = Ext.getCmp(this.config.xtypeField).getValue();
        var v = fld.getValue();
        if (t == 'combo-boolean') {
            v = (v == '1' || v == 'true' || v == 1 || v == true || v == _('yes') || v == 'yes') ? 1 : 0;
            fld.setValue(v);
        }
    }
});
Ext.reg('modx-source-value-field',MODx.form.SourceValueField);
