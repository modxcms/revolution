/**
 * Loads the Template panel
 * 
 * @class MODx.panel.Template
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-template
 */
MODx.panel.Template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/template.php'
        ,baseParams: {}
        ,id: 'modx-panel-template'
        ,class_key: 'modTemplate'
        ,template: ''
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('template_new')+'</h2>'
            ,id: 'modx-template-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure([{
            title: _('template_title')
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-template-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('template_msg')+'</p>'
                ,id: 'modx-template-msg'
            },{
                xtype: 'hidden'
                ,name: 'id'
                ,id: 'modx-template-id'
                ,value: config.template
            },{
                xtype: 'hidden'
                ,name: 'props'
                ,id: 'modx-template-props'
                ,value: null
            },{
                xtype: 'textfield'
                ,fieldLabel: _('template_name')
                ,name: 'templatename'
                ,id: 'modx-template-templatename'
                ,width: 300
                ,maxLength: 100
                ,enableKeyEvents: true
                ,allowBlank: false
                ,listeners: {
                    'keyup': {scope:this,fn:function(f,e) {
                        Ext.getCmp('template-header').getEl().update('<h2>'+_('template')+': '+f.getValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'textfield'
                ,fieldLabel: _('template_desc')
                ,name: 'description'
                ,id: 'modx-template-description'
                ,width: 300
                ,maxLength: 255
            },{
                xtype: 'modx-combo-category'
                ,fieldLabel: _('category')
                ,name: 'category'
                ,id: 'modx-template-category'
                ,width: 250
                ,value: config.category || null
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('template_lock')
                ,description: _('template_lock_msg')
                ,name: 'locked'
                ,id: 'modx-template-locked'
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('clear_cache_on_save')
                ,description: _('clear_cache_on_save_msg')
                ,name: 'clearCache'
                ,id: 'modx-template-clear-cache'
                ,inputValue: 1
                ,checked: true
            },{
                html: MODx.onTempFormRender
                ,border: false
            },{
                html: '<br />'+_('template_code')
            },{
                xtype: 'textarea'
                ,hideLabel: true
                ,name: 'content'
                ,id: 'modx-template-content'
                ,width: '95%'
                ,height: 400
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,preventRender: true
            ,collapsible: true
            ,elementPanel: 'modx-panel-template'
            ,elementId: config.template
            ,elementType: 'modTemplate'
        },{
            title: _('template_variables')
            ,itemId: 'form-template'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { autoHeight: true }
            ,items: [{
                html: '<p>'+_('template_tv_msg')+'</p>'
                ,border: false
            },{
               xtype: 'modx-grid-template-tv'
               ,preventRender: true
               ,width: '100%'
               ,template: config.template
               ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afterEdit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
               }
            }]
        }],{
            id: 'modx-template-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Template.superclass.constructor.call(this,config);
    setTimeout("Ext.getCmp('modx-element-tree').expand();",1000);
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.template === '' || this.config.template === 0 || this.initialized) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.template
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    this.getForm().setValues(r.object);
                    Ext.getCmp('modx-template-header').getEl().update('<h2>'+_('template')+': '+r.object.templatename+'</h2>');

                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    if (g) {
                        g.defaultProperties = d;
                        g.getStore().loadData(d);
                    }
                    if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
                    this.fireEvent('ready',r.object);
                    this.clearDirty();
                    this.initialized = true;
                },scope:this}
            }
        });
        return true;
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-template-tv');
        Ext.apply(o.form.baseParams,{
            tvs: g.encodeModified()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(r) {
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        Ext.getCmp('modx-grid-template-tv').getStore().commitChanges();
        this.getForm().setValues(r.result.object);
        
        var t = Ext.getCmp('modx-element-tree');
        var c = Ext.getCmp('modx-template-category').getValue();
        var u = c != '' && c != null ? 'n_template_category_'+c : 'n_type_template'; 
        t.refreshNode(u,true);
    }
    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-template-which-editor').getValue();
            MODx.request.a = MODx.action['element/template/update'];
            var u = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
            location.href = u;
        });
        this.submit();
    }    
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-template-content');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-template',MODx.panel.Template);