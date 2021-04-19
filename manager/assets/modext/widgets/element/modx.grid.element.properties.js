MODx.panel.ElementProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-element-properties'
        ,title: _('properties')
        ,header: false
        ,defaults: { collapsible: false ,autoHeight: true ,border: false }
		,layout: 'form'
        ,items: [{
            html: '<p>'+_('element_properties_desc')+'</p>'
            ,itemId: 'desc-properties'
            ,xtype: 'modx-description'
        },{
            xtype: 'modx-grid-element-properties'
			,cls:'main-wrapper'
            ,id: 'modx-grid-element-properties'
            ,itemId: 'grid-properties'
            ,autoHeight: true
            ,border: true
            ,panel: config.elementPanel
            ,elementId: config.elementId
            ,elementType: config.elementType
        },{
            layout: 'form'
            ,labelAlign: 'top'
            ,border: false
            ,cls: 'main-wrapper'
            ,items: [{
                xtype: 'xcheckbox'
                ,boxLabel: _('property_preprocess')
                ,description: MODx.expandHelp ? '' : _('property_preprocess_msg')
                ,name: 'property_preprocess'
                ,id: 'modx-element-property-preprocess'
                ,inputValue: true
                ,hideLabel: true
                ,checked: config.record.property_preprocess || 0
                ,listeners: {
                    'check':{fn:function() {Ext.getCmp(this.config.elementPanel).markDirty();},scope:this}
                }
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'modx-element-property-preprocess'
                ,html: _('property_preprocess_msg')
                ,cls: 'desc-under'
            }]
        }]
    });
    MODx.panel.ElementProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.ElementProperties,MODx.Panel);
Ext.reg('modx-panel-element-properties',MODx.panel.ElementProperties);


MODx.grid.ElementProperties = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="modx-property-description"><i>{desc_trans}</i></p>'
        )
    });
    Ext.applyIf(config,{
        title: _('properties')
        ,id: 'modx-grid-element-properties'
        ,maxHeight: 300
        ,fields: ['name','desc','xtype','options','value','lexicon','overridden','desc_trans','area','area_trans']
        ,autoExpandColumn: 'value'
        ,sortBy: 'name'
        ,anchor: '100%'
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,loadMask: true
        ,lockProperties: true
        ,plugins: [this.exp]
        ,grouping: true
        ,groupBy: 'area_trans'
        ,singleText: _('property')
        ,pluralText: _('properties')
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
        },{
            header: _('area')
            ,dataIndex: 'area_trans'
            ,id: 'area'
            ,width: 150
            ,sortable: true
            ,hidden: true
        }]
        ,tbar: [{
            text: _('property_create')
            ,id: 'modx-btn-property-create'
            ,handler: this.create
            ,scope: this
            ,disabled: true
        },{
            text: _('properties_default_locked')
            ,id: 'modx-btn-propset-lock'
            ,handler: this.togglePropertiesLock
            ,enableToggle: true
            ,pressed: true
            ,disabled: MODx.perm.unlock_element_properties ? false : true
            ,scope: this
        },'->',{
            xtype: 'modx-combo-property-set'
            ,id: 'modx-combo-property-set'
            ,baseParams: {
                action: 'Element/PropertySet/GetList'
                ,showAssociated: true
                ,elementId: config.elementId
                ,elementType: config.elementType
            }
            ,value: 0
            ,listeners: {
                'select': {fn:this.changePropertySet,scope:this}
            }
        },{
            text: _('propertyset_add')
            ,handler: this.addPropertySet
            ,scope: this
        },{
            text: _('propertyset_save')
            ,cls: 'primary-button'
            ,handler: this.save
            ,scope: this
            ,hidden: MODx.request.id ? false : true
        }]
        ,bbar: [{
            text: _('property_revert_all')
            ,id: 'modx-btn-property-revert-all'
            ,handler: this.revertAll
            ,scope:this
            ,disabled: true
        },{
            text: _('import')
            ,handler: this.importProperties
            ,scope: this
        },{
            text: _('export')
            ,handler: this.exportProperties
            ,scope: this
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
    MODx.grid.ElementProperties.superclass.constructor.call(this,config);
    this.on('afteredit', this.propertyChanged, this);
    this.on('afterRemoveRow', this.propertyChanged, this);
    this.on('render',function() {
        this.mask = new Ext.LoadMask(this.getEl());
    },this);

    if (this.config.lockProperties) {
        this.on('render',function() {
            this.lockMask = MODx.load({
                xtype: 'modx-lockmask'
                ,el: this.getGridEl()
                ,msg: _('properties_default_locked')
            });
            this.lockMask.toggle();
        },this);
    }
};
Ext.extend(MODx.grid.ElementProperties,MODx.grid.LocalProperty,{
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
            case 'file': return _('file'); break;
            case 'color': return _('color'); break;
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

    ,save: function() {
        var d = this.encode();
        var cb = Ext.getCmp('modx-combo-property-set');
        if (!cb) {
            this.getStore().commitChanges();
            this.onDirty();
            return true;
        }
        var p = {
            action: 'Element/PropertySet/UpdateFromElement'
            ,id: cb.getValue()
            ,data: d
        };
        if (this.config.elementId) {
            Ext.apply(p,{
                elementId: this.config.elementId
                ,elementType: this.config.elementType
            });
        }
        try {
            if (!this.mask) {
                this.mask = new Ext.LoadMask(this.getEl());
            }
            if (this.mask) { this.mask.show(); }
        } catch (e) { }

        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: p
            ,listeners: {
                'success': {fn:function(r) {
                    this.getStore().commitChanges();
                    this.changePropertySet(cb);
                    this.onDirty();
                    if (this.mask) { this.mask.hide(); }
                    MODx.msg.status({
                        title: _('success')
                        ,message: _('save_successful')
                        ,dontHide: r.message != '' ? true : false
                    });
                },scope:this}
            }
        });
    }

    ,addPropertySet: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-set-add'
            ,record: {
                elementId: this.config.elementId != 0 ? this.config.elementId : ''
                ,elementType: this.config.elementType
            }
            ,listeners: {
                'success': {fn:function(o) {
                    var cb = Ext.getCmp('modx-combo-property-set');
                    cb.store.reload({
                        callback: function() {
                            cb.setValue(o.a.result.object.id);
                            this.changePropertySet(cb);
                        }
                        ,scope: this
                    });
                    this.onDirty();
                },scope:this}
            }
        });
    }

    ,togglePropertiesLock: function() {
        var ps = Ext.getCmp('modx-combo-property-set').getValue();
        if (ps == 0 || ps == _('default')) {
            Ext.getCmp('modx-btn-propset-lock').setText(this.lockMask.locked ? _('properties_default_unlocked') : _('properties_default_locked'));
            this.lockMask.toggle();
            this.toggleButtons(this.lockMask.locked);
        }
    }

    ,toggleButtons: function(v) {
        var btn = Ext.getCmp('modx-btn-property-create');
        if (btn) {
            Ext.getCmp('modx-btn-property-create').setDisabled(v);
            Ext.getCmp('modx-btn-property-revert-all').setDisabled(v);
        }
    }

    ,changePropertySet: function(cb) {
        var ps = cb.getValue();
        var lockbtn = Ext.getCmp('modx-btn-propset-lock');
        if (ps == 0 || ps == _('default')) {
            if (MODx.perm.unlock_element_properties) {
                if (lockbtn) { lockbtn.setDisabled(false); }
            }
            if (this.lockMask && this.lockMask.locked) {
                this.lockMask.show();
                this.toggleButtons(true);
            }
        } else {
            if (lockbtn) { lockbtn.setDisabled(true); }
            if (this.lockMask) this.lockMask.hide();
            this.toggleButtons(false);
        }

        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'Element/PropertySet/Get'
                ,id: ps
                ,elementId: this.config.elementId
                ,elementType: this.config.elementType
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var s = this.getStore();
                    var data = Ext.decode(r.object.data);
                    s.removeAll();
                    s.loadData(data);
                },scope:this}
            }
        });
    }

    ,create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-create'
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
                        ,overridden: this.isDefaultPropSet() ? 0 : 2
                        ,area: r.area
                        ,area_trans: r.area
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
            xtype: 'modx-window-element-property-update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(r) {
                    var def = this.isDefaultPropSet();
                    var s = this.getStore();
                    var rec = s.getAt(this.menu.recordIndex);
                    rec.set('name',r.name);
                    rec.set('desc',r.desc);
                    rec.set('desc_trans', r.desc);
                    rec.set('xtype',r.xtype);
                    rec.set('options',r.options);
                    rec.set('value',r.value);
                    rec.set('lexicon',r.lexicon);
                    rec.set('overridden',r.overridden == 2 ? 2 : (!def ? 1 : 0));
                    rec.set('area',r.area);
                    rec.set('area_trans',r.area);
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
                    rec.set('desc_trans',d[1]);
                    rec.set('xtype',d[2]);
                    rec.set('options',d[3]);
                    rec.set('value',d[4]);
                    rec.set('overridden',0);
                    rec.set('area',d[5]);
                    rec.set('area_trans',d[5]);
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

    ,exportProperties: function (btn,e) {
        var id = Ext.getCmp('modx-combo-property-set').getValue();
        location.href = MODx.config.connector_url+'?action=Element/ExportProperties&download=1&id='+id+'&data='+this.encode()+'&HTTP_MODAUTH='+MODx.siteId;
    }

    ,importProperties: function (btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-properties-import'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(o) {
                    var s = this.getStore();
                    var data = o.a.result.object;
                    /* handle <> in values, desc */
                    for (var i in data) {
                        if (data[i][4]) { data[i][4] = data[i][4].replace(/&gt;/g,'>').replace(/&lt;/g,'<'); }
                        if (data[i][5]) { data[i][5] = data[i][5].replace(/&gt;/g,'>').replace(/&lt;/g,'<'); }
                        if (data[i][1]) { data[i][1] = data[i][1].replace(/&gt;/g,'>').replace(/&lt;/g,'<'); }
                    }
                    s.loadData(data);
                    /* mark fields dirty */
                    var recs = s.getRange(0,s.getTotalCount());
                    for (var i=0;i<recs.length;i++) {
                        recs[i].markDirty();
                    }
                    this.getView().refresh();
                },scope:this}
            }
        });
    }

    ,_showMenu: function(g,ri,e) {
        var sm = this.getSelectionModel();
        if (sm.getSelections().length > 1) {
            e.stopEvent();
            e.preventDefault();
            this.menu.removeAll();
            this.addContextMenuItem([{
                text: _('properties_remove')
                ,handler: this.removeMultiple
                ,scope: this
            }]);
            this.menu.show(e.target);
        } else {
            MODx.grid.ElementProperties.superclass._showMenu.call(this,g,ri,e);
        }
    }

    ,isDefaultPropSet: function() {
        var ps = Ext.getCmp('modx-combo-property-set').getValue();
        return (ps == 0 || ps == _('default'));
    }

    ,getMenu: function() {
        var def = this.isDefaultPropSet();

        var r = this.menu.record;
        var m = [];
        m.push({
            text: _('property_update')
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
        if ((r.overridden == 2 && !def) || (r.overridden != 1 && def) || (!r.overridden && !def)) {
            m.push({
                text: _('property_remove')
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
        var hf = this.config.hiddenPropField || 'props';
        ep.getForm().findField(hf).setValue('1');
        ep.fireEvent('fieldChange',{
            field: hf
            ,form: ep.getForm()
        });
        return true;
    }
});
Ext.reg('modx-grid-element-properties',MODx.grid.ElementProperties);


MODx.grid.ElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_options')
        ,id: 'modx-grid-element-property-options'
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
            text: _('property_option_create')
            ,cls: 'primary-button'
            ,handler: this.create
            ,scope: this
        }]
    });
    MODx.grid.ElementPropertyOption.superclass.constructor.call(this,config);
    this.optRecord = Ext.data.Record.create([{name: 'text'},{name: 'value'}]);
};
Ext.extend(MODx.grid.ElementPropertyOption,MODx.grid.LocalGrid,{
    create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-element-property-option-create'
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
            text: _('property_option_remove')
            ,scope: this
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('property_option_remove_confirm')
            }])
        }];
    }
});
Ext.reg('modx-grid-element-property-options',MODx.grid.ElementPropertyOption);

/**
 * @class MODx.window.CreateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-create
 */
MODx.window.CreateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_create')
        ,id: 'modx-window-element-property-create'
        ,width: 600
        ,saveBtnText: _('done')
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .6
                ,items: [{
                    fieldLabel: _('name')
                    ,description: MODx.expandHelp ? '' : _('property_name_desc')
                    ,name: 'name'
                    ,id: 'modx-cep-name'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                    ,allowBlank: false
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-cep-name'
                    ,html: _('property_name_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('description')
                    ,description: MODx.expandHelp ? '' : _('property_description_desc')
                    ,name: 'desc'
                    ,id: 'modx-cep-desc'
                    ,xtype: 'textarea'
                    ,anchor: '100%'
                    ,height: 120
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-cep-description'
                    ,html: _('property_description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .4
                ,items: [{
                    fieldLabel: _('type')
                    ,description: MODx.expandHelp ? '' : _('property_xtype_desc')
                    ,name: 'xtype'
                    ,id: 'modx-cep-xtype'
                    ,xtype: 'modx-combo-xtype'
                    ,anchor: '100%'
                    ,listeners: {
                        'select': {fn:function(cb) {
                            var g = Ext.getCmp('modx-cep-grid-element-property-options');
                            if (!g) return;
                            if (cb.getValue() == 'list' || cb.getValue() == 'color') {
                               g.show();
                            } else {
                               g.hide();
                            }
                            this.syncSize();
                        },scope:this}
                    }
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-cep-xtype'
                    ,html: _('property_xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('lexicon')
                    ,description: MODx.expandHelp ? '' : _('property_lexicon_desc')
                    ,name: 'lexicon'
                    ,id: 'modx-cep-lexicon'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-cep-lexicon'
                    ,html: _('property_lexicon_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area')
                    ,description: MODx.expandHelp ? '' : _('property_area_desc')
                    ,name: 'area'
                    ,id: 'modx-cep-area'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-cep-area'
                    ,html: _('property_area_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'modx-element-value-field'
            ,xtypeField: 'modx-cep-xtype'
            ,id: 'modx-cep-value'
            ,anchor: '100%'
        },{
            xtype: 'modx-grid-element-property-options'
            ,id: 'modx-cep-grid-element-property-options'
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.CreateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.CreateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        var g = Ext.getCmp('modx-cep-grid-element-property-options');
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
        var g = Ext.getCmp('modx-cep-grid-element-property-options');
        g.getStore().removeAll();
        g.hide();
        this.syncSize();
        this.center();
    }
});
Ext.reg('modx-window-element-property-create',MODx.window.CreateElementProperty);



/**
 * @class MODx.window.UpdateElementProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-update
 */
MODx.window.UpdateElementProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_update')
        ,id: 'modx-window-element-property-update'
        ,width: 600
        ,saveBtnText: _('done')
        ,forceLayout: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .6
                ,items: [{
                    fieldLabel: _('name')
                    ,description: MODx.expandHelp ? '' : _('property_name_desc')
                    ,name: 'name'
                    ,id: 'modx-uep-name'
                    ,xtype: 'textfield'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-uep-name'
                    ,html: _('property_name_desc')
                    ,cls: 'desc-under'
                },{
                    fieldLabel: _('description')
                    ,description: MODx.expandHelp ? '' : _('property_description_desc')
                    ,name: 'desc'
                    ,id: 'modx-uep-desc'
                    ,xtype: 'textarea'
                    ,anchor: '100%'
                    ,height: 120
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-uep-description'
                    ,html: _('property_description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .4
                ,items: [{
                    fieldLabel: _('type')
                    ,description: MODx.expandHelp ? '' : _('property_xtype_desc')
                    ,name: 'xtype'
                    ,xtype: 'modx-combo-xtype'
                    ,id: 'modx-uep-xtype'
                    ,anchor: '100%'
                    ,listeners: {
                        'select': {fn:function(cb) {
                            var g = Ext.getCmp('modx-uep-grid-element-property-options');
                            if (!g) return;
                            var v = cb.getValue();
                            if (v == 'list' || v == 'color') {
                                g.show();
                            } else {
                                g.hide();
                            }
                            this.syncSize();
                        },scope:this}
                    }
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-uep-xtype'
                    ,html: _('property_xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('lexicon')
                    ,description: MODx.expandHelp ? '' : _('property_lexicon_desc')
                    ,name: 'lexicon'
                    ,id: 'modx-uep-lexicon'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-uep-lexicon'
                    ,html: _('property_lexicon_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area')
                    ,description: MODx.expandHelp ? '' : _('property_area_desc')
                    ,name: 'area'
                    ,id: 'modx-uep-area'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-uep-area'
                    ,html: _('property_area_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'hidden'
            ,name: 'overridden'
            ,id: 'modx-uep-overridden'
        },{
            xtype: 'modx-element-value-field'
            ,xtypeField: 'modx-uep-xtype'
            ,name: 'value'
            ,id: 'modx-uep-value'
            ,anchor: '100%'
        },{
            id: 'modx-uep-grid-element-property-options'
            ,xtype: 'modx-grid-element-property-options'
            ,autoHeight: true
        }]
        ,keys: []
    });
    MODx.window.UpdateElementProperty.superclass.constructor.call(this,config);
    this.on('show',this.onShow,this);
};
Ext.extend(MODx.window.UpdateElementProperty,MODx.Window,{
    submit: function() {
        var v = this.fp.getForm().getValues();

        var g = Ext.getCmp('modx-uep-grid-element-property-options');
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
        var g = Ext.getCmp('modx-uep-grid-element-property-options');
        if (!g) return;
        if (this.fp.getForm().findField('xtype').getValue() == 'list' || this.fp.getForm().findField('xtype').getValue() == 'color') {
            g.show();
        } else {
            g.hide();
        }
        g.getStore().removeAll();
        var gp = Ext.getCmp('modx-grid-element-properties');
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
Ext.reg('modx-window-element-property-update',MODx.window.UpdateElementProperty);

/**
 * @class MODx.window.CreateElementPropertyOption
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-option-create
 */
MODx.window.CreateElementPropertyOption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_option_create')
        ,id: 'modx-window-element-property-option-create'
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
    MODx.window.CreateElementPropertyOption.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateElementPropertyOption,MODx.Window,{
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
Ext.reg('modx-window-element-property-option-create',MODx.window.CreateElementPropertyOption);



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
                ,[_('file'),'file']
                ,[_('color'),'color']
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




MODx.form.ElementValueField = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        fieldLabel: _('value')
        ,name: 'value'
        ,xtype: 'textfield'
    });
    MODx.form.ElementValueField.superclass.constructor.call(this,config);
    this.config = config;
    this.on('change',this.checkValue,this);
};
Ext.extend(MODx.form.ElementValueField,Ext.form.TextField,{
    checkValue: function(fld,nv,ov) {
        var t = Ext.getCmp(this.config.xtypeField).getValue();
        var v = fld.getValue();
        if (t == 'combo-boolean') {
            v = (v == '1' || v == 'true' || v == 1 || v == true || v == _('yes') || v == 'yes') ? 1 : 0;
            fld.setValue(v);
        }
    }
});
Ext.reg('modx-element-value-field',MODx.form.ElementValueField);


MODx.combo.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'propertyset'
        ,hiddenName: 'propertyset'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/PropertySet/GetList'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description','properties']
        ,editable: false
        ,value: 0
        ,pageSize: 10
    });
    MODx.combo.PropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.PropertySet,MODx.combo.ComboBox);
Ext.reg('modx-combo-property-set',MODx.combo.PropertySet);

/**
 * @class MODx.window.AddPropertySet
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-window-element-property-set-add
 */
MODx.window.AddPropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('propertyset_add')
        ,id: 'modx-window-element-property-set-add'
        ,url: MODx.config.connector_url
        ,action: 'Element/PropertySet/Associate'
        ,autoHeight: true // makes window grow when the fieldset is toggled
        ,fields: [{
            xtype: 'hidden'
            ,name: 'elementId'
            ,id: 'modx-aps-elementId'
        },{
            xtype: 'hidden'
            ,name: 'elementType'
            ,id: 'modx-aps-elementType'
        },{
            html: _('propertyset_panel_desc')
            ,xtype: 'modx-description'

        },MODx.PanelSpacer,{
            xtype: 'modx-combo-property-set'
            ,fieldLabel: _('propertyset')
            ,name: 'propertyset'
            ,id: 'modx-aps-propertyset'
            ,anchor: '100%'
            ,baseParams: {
                action: 'Element/PropertySet/GetList'
                ,showNotAssociated: true
                ,elementId: config.record.elementId
                ,elementType: config.record.elementType
            }
        },{
            xtype: 'hidden'
            ,name: 'propertyset_new'
            ,id: 'modx-aps-propertyset-new'
            ,value: false
        },{
            xtype: 'fieldset'
            ,title: _('propertyset_create_new')
            ,autoHeight: true
            ,checkboxToggle: true
            ,collapsed: true
            ,forceLayout: true
            ,id: 'modx-aps-propertyset-new-fs'
            ,listeners: {
                'expand': {fn:function(p) {
                    Ext.getCmp('modx-aps-propertyset-new').setValue(true);
                    this.center(); // re-centers window on screen after height changed
                },scope:this}
                ,'collapse': {fn:function(p) {
                    Ext.getCmp('modx-aps-propertyset-new').setValue(false);
                    this.center(); // re-centers window on screen after height changed
                },scope:this}
            }
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('name')
                ,name: 'name'
                ,id: 'modx-aps-name'
                ,anchor: '100%'
            },{
                xtype: 'textarea'
                ,fieldLabel: _('description')
                ,name: 'description'
                ,id: 'modx-aps-description'
                ,anchor: '100%'
                ,grow: true
            }]
        }]
    });
    MODx.window.AddPropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.AddPropertySet,MODx.Window);
Ext.reg('modx-window-element-property-set-add',MODx.window.AddPropertySet);

MODx.window.ImportProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('import')
        ,id: 'modx-window-properties-import'
        ,url: MODx.config.connector_url
        ,action: 'Element/ImportProperties'
        ,fileUpload: true
        ,saveBtnText: _('import')
        ,fields: [{
            html: _('properties_import_msg')
            ,id: 'modx-impp-desc'
            ,style: 'margin-bottom: 10px;'
            ,xtype: 'modx-description'
        },{
            xtype: 'fileuploadfield'
            ,fieldLabel: _('file')
            ,buttonText: _('upload.buttons.upload')
            ,name: 'file'
            ,id: 'modx-impp-file'
            ,anchor: '100%'
        }]
    });
    MODx.window.ImportProperties.superclass.constructor.call(this,config);

    // Trigger "fileselected" event
    var fp = Ext.getCmp('modx-impp-file');
    var onFileUploadFieldFileSelected = function(fp, fakeFilePath) {
        var fileApi = fp.fileInput.dom.files;
        fp.el.dom.value = (typeof fileApi != 'undefined') ? fileApi[0].name : fakeFilePath.replace("C:\\fakepath\\", "");
    };
    fp.on('fileselected', onFileUploadFieldFileSelected);

};
Ext.extend(MODx.window.ImportProperties,MODx.Window);
Ext.reg('modx-window-properties-import',MODx.window.ImportProperties);
