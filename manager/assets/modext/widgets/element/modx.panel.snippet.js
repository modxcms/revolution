/**
 * @class MODx.panel.Snippet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-snippet
 */
MODx.panel.Snippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/snippet.php'
        ,baseParams: {}
        ,id: 'modx-panel-snippet'
        ,class_key: 'modSnippet'
        ,plugin: ''
        ,bodyStyle: ''
        ,allowDrop: false
        ,items: [{
            html: '<h2>'+_('snippet_new')+'</h2>'
            ,id: 'modx-snippet-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure([{
            title: _('snippet_title')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,bodyStyle: 'padding: 15px;'
            ,layout: 'form'
            ,id: 'modx-snippet-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('snippet_msg')+'</p>'
                ,id: 'modx-snippet-msg'
            },{
                xtype: 'hidden'
                ,name: 'id'
                ,id: 'modx-snippet-id'
                ,value: config.snippet
            },{
                xtype: 'hidden'
                ,name: 'props'
                ,id: 'modx-snippet-props'
                ,value: null
            },{
                xtype: 'textfield'
                ,fieldLabel: _('snippet_name')
                ,name: 'name'
                ,id: 'modx-snippet-name'
                ,width: 300
                ,maxLength: 255
                ,enableKeyEvents: true
                ,allowBlank: false
                ,listeners: {
                    'keyup': {scope:this,fn:function(f,e) {
                        Ext.getCmp('modx-snippet-header').getEl().update('<h2>'+_('snippet')+': '+f.getValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'textfield'
                ,fieldLabel: _('snippet_desc')
                ,name: 'description'
                ,id: 'modx-snippet-description'
                ,width: 300
                ,maxLength: 255
            },{
                xtype: 'modx-combo-category'
                ,fieldLabel: _('category')
                ,name: 'category'
                ,id: 'modx-snippet-category'
                ,width: 250
                ,value: config.category || null
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('snippet_lock')
                ,description: _('snippet_lock_msg')
                ,name: 'locked'
                ,id: 'modx-snippet-locked'
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('clear_cache_on_save')
                ,description: _('clear_cache_on_save_msg')
                ,name: 'clearCache'
                ,id: 'modx-snippet-clear-cache'
                ,inputValue: 1
                ,checked: true
            },{
                html: MODx.onSnipFormRender
                ,border: false
            },{
                html: '<br />'+_('snippet_code')
            },{
                xtype: 'textarea'
                ,hideLabel: true
                ,name: 'snippet'
                ,id: 'modx-snippet-snippet'
                ,width: '95%'
                ,height: 400
                ,value: "<?php"
                
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-snippet'
            ,elementId: config.snippet
            ,elementType: 'modSnippet'
        }])]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.snippet === '' || this.config.snippet === 0 || this.initialized) {
            this.fireEvent('ready');
            return;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.snippet
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    r.object.snippet = "<?php\n"+r.object.snippet+"\n?>";
                    this.getForm().setValues(r.object);
                    Ext.getCmp('modx-snippet-header').getEl().update('<h2>'+_('snippet')+': '+r.object.name+'</h2>');
                    
                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);

                    if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
                    this.fireEvent('ready',r.object);
                    this.clearDirty();
                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        this.cleanupEditor();
        Ext.apply(o.form.baseParams,{
            propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(r) {
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        this.getForm().setValues(r.result.object);
        
        var t = Ext.getCmp('modx-element-tree');
        var c = Ext.getCmp('modx-snippet-category').getValue();
        var u = c != '' && c != null ? 'n_snippet_category_'+c : 'n_type_snippet'; 
        t.refreshNode(u,true);
    }    
    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-snippet-which-editor').getValue();
            MODx.request.a = MODx.action['element/snippet/update'];
            var u = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
            location.href = u;
        });
        this.submit();
    }    
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-snippet-snippet');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-snippet',MODx.panel.Snippet);