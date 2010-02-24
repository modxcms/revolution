Ext.ns('MODx.rte.window');

MODx.rte.window.ButtonWindow = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        closeAction: 'hide'
        ,cls: 'modx-rte-btn-window'
        ,allowDrop: true
        ,buttons: [{
            text: _('insert')
            ,handler: config.onSubmit || this.submit
            ,scope: config.scope || this
        }, {
            text: _('cancel')
            ,handler: function() { this.hide(); }
            ,scope: this
        }]
    });
    MODx.rte.window.ButtonWindow.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents('submit','invalid','formNotFound');
};
Ext.extend(MODx.rte.window.ButtonWindow,MODx.Window,{
    submit: function(){
        var frm = this.fp.getForm();
        if (frm) {
            if (frm.isValid()) {
                this.fireEvent('submit',frm.getValues());
                frm.reset();
                this.hide();
            } else {
                this.fireEvent('invalid',frm.getValues());
            }
        } else {
            this.fireEvent('formNotFound');
        }
    }
});

MODx.rte.window.SpecialChar = function(config) {
    config = config || {};
    
    this.loadStore(config);    
    Ext.applyIf(config,{
        title: _('btn_specchar_ovf')
        ,width: 436
        ,autoHeight: true
        ,layout: 'fit'
        ,items: [{
            xtype: 'dataview'
            ,store: this.charStore
            ,ref: '../charView'
            ,autoHeight: true
            ,multiSelect: true
            ,tpl: new Ext.XTemplate('<tpl for="."><div class="char-item">{char}</div></tpl><div class="x-clear"></div>')
            ,overClass: 'char-over'
            ,itemSelector: 'div.char-item'
            ,listeners: {
                'dblclick': {fn:function(t, i, n, e){
                    this.fireEvent('submit',{
                        chars: this.charView.getSelectedRecords()
                    });
                    this.hide();
                },scope: this}
            }
        }]
        ,buttons: [{
            text: _('insert')
            ,handler: function(){
                this.fireEvent('submit',{
                    chars: this.charView.getSelectedRecords()
                });
                this.hide();
            },
            scope: this
        }, {
            text: _('cancel')
            ,handler: function() { this.hide(); }
            ,scope: this
        }]
    
    });
    MODx.rte.window.SpecialChar.superclass.constructor.call(this,config);
};
Ext.extend(MODx.rte.window.SpecialChar,MODx.rte.window.ButtonWindow,{
    specialChars: []
    ,charRange: [160, 256]
    ,loadStore: function(config) {
        if (!this.charStore) {
            if (this.specialChars.length) {
                Ext.each(this.specialChars, function(c, i){
                    this.specialChars[i] = ['&#' + c + ';'];
                }, this);
            }
            for (i = this.charRange[0]; i < this.charRange[1]; i++) {
                this.specialChars.push(['&#' + i + ';']);
            }
            this.charStore = new Ext.data.ArrayStore({
                fields: ['char']
                ,data: this.specialChars
            });
        }
    }
});
Ext.reg('modx-rte-window-special-char',MODx.rte.window.SpecialChar);


MODx.rte.window.HR = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('hr_title')
        ,fields: [{
            html: '<p>'+_('hr_msg')+'</p><br />'
        }, {
            xtype: 'textfield'
            ,maskRe: /[0-9]|%/
            ,regex: /^[1-9][0-9%]{1,3}/
            ,fieldLabel: _('opt_width')
            ,name: 'hrwidth'
            ,width: 60
            ,listeners: {
                specialkey: function(f, e){
                    if ((e.getKey() == e.ENTER || e.getKey() == e.RETURN) && f.isValid()) {
                        this.doInsertHR();
                    }else{
                        f.getEl().frame();
                    }
                },
                scope: this
            }
        }]
    });
    MODx.rte.window.HR.superclass.constructor.call(this,config);
    this.on('invalid',function() {
        var frm = this.getComponent('insert-hr').getForm();
        if (frm) {
            frm.findField('hrwidth').getEl().frame();
        }
    },this);
};
Ext.extend(MODx.rte.window.HR,MODx.rte.window.ButtonWindow);
Ext.reg('modx-rte-window-hr',MODx.rte.window.HR);


MODx.rte.window.Table = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('btn_table_ovf')
        ,width: 550
        ,labelWidth: 90
        ,fields: [{
            layout:'column'
            ,border: false
            ,anchor: '100%'
            ,items:[{
                columnWidth: .50
                ,layout: 'form'
                ,border: false
                ,labelWidth: 90
                ,items: [{
                    xtype: 'numberfield'
                    ,fieldLabel: _('opt_rows')
                    ,name: 'row'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'numberfield'
                    ,fieldLabel: _('opt_cellpadding')
                    ,name: 'cellpadding'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'combo'
                    ,fieldLabel: _('opt_align')
                    ,name: 'align'
                    ,forceSelection: true
                    ,editable: false
                    ,anchor: '90%'
                    ,mode: 'local'
                    ,store: new Ext.data.ArrayStore({
                        autoDestroy: true
                        ,fields: ['v', 'd']
                        ,data: [
                            ['',_('opt_none')]
                            ,['center',_('opt_center')]
                            ,['left',_('opt_left')]
                            ,['right',_('opt_right')]
                        ]
                    })
                    ,triggerAction: 'all'
                    ,value: 'none'
                    ,displayField: 'd'
                    ,valueField: 'v'
                    ,width: 90
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_width')
                    ,name: 'width'
                    ,allowBlank: false
                    ,allowDecimals: false
                    ,width: 80
                }]
            },{
                columnWidth: .50
                ,layout: 'form'
                ,border: false
                ,labelWidth: 90
                ,items: [{
                    xtype: 'numberfield'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,fieldLabel: _('opt_cols')
                    ,name: 'col'
                    ,width: 60
                },{
                    xtype: 'numberfield'
                    ,fieldLabel: _('opt_cellspacing')
                    ,name: 'cellspacing'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'combo'
                    ,fieldLabel: _('opt_border')
                    ,name: 'border'
                    ,forceSelection: true
                    ,editable: false
                    ,anchor: '90%'
                    ,mode: 'local'
                    ,store: new Ext.data.ArrayStore({
                        autoDestroy: true
                        ,fields: ['spec', 'val']
                        ,data: [
                            ['none', 'None']
                            ,['1px solid #000', 'Sold Thin']
                            ,['2px solid #000', 'Solid Thick']
                            ,['1px dashed #000', 'Dashed']
                            ,['1px dotted #000', 'Dotted']
                        ]
                    })
                    ,triggerAction: 'all'
                    ,value: 'none'
                    ,displayField: 'val'
                    ,valueField: 'spec'
                    ,width: 90
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_height')
                    ,name: 'height'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 80
                }]
            }]
        },{
            xtype: 'textfield'
            ,fieldLabel: _('opt_class')
            ,name: 'class'
            ,allowBlank: true
            ,width: 300
        }]
    });
    MODx.rte.window.Table.superclass.constructor.call(this,config);
};
Ext.extend(MODx.rte.window.Table,MODx.rte.window.ButtonWindow);
Ext.reg('modx-rte-window-table',MODx.rte.window.Table);




MODx.rte.window.Link = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('btn_addlink')
        ,formId: 'insert-link'
        ,itemId: 'insert-link'
        ,width: 550
        ,fields: [{
            bodyStyle: 'padding: 10px;'
            ,xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: false
            }
            ,deferredRender: false
            ,items: [{
                title: _('opt_general')
                ,layout: 'form'
                ,labelWidth: 90
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_url')
                    ,name: 'href'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_title')
                    ,name: 'title'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_class')
                    ,name: 'class'
                    ,anchor: '100%'
                }]
            },{
                title: _('opt_advanced')
                ,layout: 'form'
                ,labelWidth: 90
                ,defaults: {
                    anchor: '100%'
                }
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_id')
                    ,name: 'id'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_style')
                    ,name: 'style'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_rel')
                    ,name: 'rel'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_rev')
                    ,name: 'rev'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_other_attr')
                    ,name: 'other'
                }]
            }]
        }]
    });
    MODx.rte.window.Link.superclass.constructor.call(this,config);
};
Ext.extend(MODx.rte.window.Link,MODx.rte.window.ButtonWindow);
Ext.reg('modx-rte-window-link',MODx.rte.window.Link);



MODx.rte.window.Image = function(config) {
    config = config || {};
    this.ident = Ext.id();
    Ext.applyIf(config,{
        title: _('btn_image')
        ,formId: 'insert-image'
        ,itemId: 'insert-image'
        ,width: 550
        ,fields: [{
            bodyStyle: 'padding: 10px;'
            ,xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: false
            }
            ,deferredRender: false
            ,items: [{
                title: _('opt_general')
                ,layout: 'form'
                ,labelWidth: 90
                ,items: [{
                    xtype: 'modx-combo-browser'
                    ,fieldLabel: _('opt_url')
                    ,name: 'src'
                    ,anchor: '100%'
                    ,listeners: {
                        'select': {fn:function(r) {
                            data = this.fp.getForm().getValues();
                            data.src = r.url;
                            this.showPreview(data);
                        },scope:this}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_title')
                    ,name: 'title'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_alt')
                    ,name: 'alt'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_class')
                    ,name: 'class'
                    ,anchor: '100%'
                }]
            },{
                title: _('opt_appearance')
                ,layout: 'form'
                ,labelWidth: 90
                ,defaults: {
                    anchor: '100%'
                }
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_width')
                    ,name: 'width'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_height')
                    ,name: 'height'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_border')
                    ,name: 'border'
                    ,allowBlank: true
                    ,allowDecimals: false
                    ,width: 60
                },{
                    xtype: 'combo'
                    ,fieldLabel: _('opt_align')
                    ,name: 'align'
                    ,forceSelection: true
                    ,editable: false
                    ,anchor: '90%'
                    ,mode: 'local'
                    ,store: new Ext.data.ArrayStore({
                        autoDestroy: true
                        ,fields: ['v', 'd']
                        ,data: [
                            ['',_('opt_none')]
                            ,['center',_('opt_center')]
                            ,['left',_('opt_left')]
                            ,['right',_('opt_right')]
                        ]
                    })
                    ,triggerAction: 'all'
                    ,value: 'none'
                    ,displayField: 'd'
                    ,valueField: 'v'
                    ,width: 90
                }]
            },{
                title: _('opt_advanced')
                ,layout: 'form'
                ,labelWidth: 90
                ,defaults: {
                    anchor: '100%'
                }
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_id')
                    ,name: 'id'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_style')
                    ,name: 'style'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('opt_other_attr')
                    ,name: 'other'
                }]
            }]
        },{
            html: '<p>'+_('opt_preview')+'</p>'
            ,cls: 'modx-rte-image-preview-header'
            ,border: false
            ,hideLabel: true
        },{
            hideLabel: true
            ,id: this.ident+'-preview'
            ,cls: 'modx-rte-image-preview'
            ,autoScroll: true
            ,border: true
            ,anchor: '100%'
            ,height: 300
            ,html: ''
        }]
    });
    MODx.rte.window.Image.superclass.constructor.call(this,config);
    this.on('show',function() {
        var v = this.fp.getForm().getValues();
        v.src = MODx.config.site_url+v.src;
        this.showPreview(v);
        this.syncSize();
        this.center();
    },this)
};
Ext.extend(MODx.rte.window.Image,MODx.rte.window.ButtonWindow,{
    showPreview: function(data) {
        var v = data;
        v.tag = 'img';
        var m = Ext.DomHelper.markup(v);
        Ext.get(this.ident+'-preview').update(m);
    }
});
Ext.reg('modx-rte-window-image',MODx.rte.window.Image);


