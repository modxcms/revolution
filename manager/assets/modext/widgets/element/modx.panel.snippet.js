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
		,cls: 'container'
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
            ,layout: 'form'
            ,id: 'modx-snippet-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('snippet_msg')+'</p>'
                ,id: 'modx-snippet-msg'
				,bodyCssClass: 'panel-desc'
            },{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,items: [{
					xtype: 'hidden'
					,name: 'id'
					,id: 'modx-snippet-id'
					,value: config.snippet
				},{
					xtype: 'hidden'
					,name: 'props'
					,id: 'modx-snippet-props'
					,value: config.record.props || null
				},{
					xtype: 'textfield'
					,fieldLabel: _('snippet_name')
					,name: 'name'
					,id: 'modx-snippet-name'
					,width: 300
					,maxLength: 255
					,enableKeyEvents: true
					,allowBlank: false
					,value: config.record.name
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
					,value: config.record.description || ''
				},{
					xtype: 'modx-combo-category'
					,fieldLabel: _('category')
					,name: 'category'
					,id: 'modx-snippet-category'
					,width: 250
					,value: config.record.category || 0
				},{
					xtype: 'xcheckbox'
					,fieldLabel: _('snippet_lock')
					,description: _('snippet_lock_msg')
					,name: 'locked'
					,id: 'modx-snippet-locked'
					,inputValue: 1
					,checked: config.record.locked || 0
				},{
					xtype: 'xcheckbox'
					,fieldLabel: _('clear_cache_on_save')
					,description: _('clear_cache_on_save_msg')
					,name: 'clearCache'
					,id: 'modx-snippet-clear-cache'
					,inputValue: 1
					,checked: Ext.isDefined(config.record.clearCache) || true
				},{
					html: MODx.onSnipFormRender
					,border: false
				}]
            },{
				xtype: 'panel'
				,border: false
				,layout: 'form'
				,cls:'main-wrapper'
				,labelAlign: 'top'
				,items: [{
					xtype: 'textarea'
					,fieldLabel: _('snippet_code')
					,name: 'snippet'
					,id: 'modx-snippet-snippet'
					,anchor: '100%'
					,height: 400
					,value: config.record.snippet || "<?php\n"
                }]
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-snippet'
            ,elementId: config.snippet
            ,elementType: 'modSnippet'
        }],{
            id: 'modx-snippet-tabs'
        })]
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
        if (!this.initialized) { this.getForm().setValues(this.config.record); }
        if (!Ext.isEmpty(this.config.record.name)) {
            Ext.getCmp('modx-snippet-header').getEl().update('<h2>'+_('snippet')+': '+this.config.record.name+'</h2>');
        }
        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }
        this.fireEvent('ready',this.config.record);
        if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
        this.clearDirty();
        this.initialized = true;
        return true;
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
        if (t) {
            var c = Ext.getCmp('modx-snippet-category').getValue();
            var u = c != '' && c != null && c != 0 ? 'n_snippet_category_'+c : 'n_type_snippet';
            var node = t.getNodeById('n_snippet_element_' + Ext.getCmp('modx-snippet-id').getValue() + '_' + r.result.object.previous_category);
            if (node) node.destroy();
            t.refreshNode(u,true);
        }
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