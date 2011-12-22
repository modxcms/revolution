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
		,cls: 'container form-with-labels'
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
                layout: 'column'
                ,border: false
                ,defaults: {
                    layout: 'form'
                    ,labelAlign: 'top'
                    ,anchor: '100%'
                    ,border: false
                    ,cls:'main-wrapper'
                    ,labelSeparator: ''
                }
				,items: [{
                    columnWidth: .6
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
                        ,fieldLabel: _('name')+'<span class="required">*</span>'
                        ,description: MODx.expandHelp ? '' : _('snippet_desc_name')
                        ,name: 'name'
                        ,id: 'modx-snippet-name'
                        ,anchor: '100%'
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
                        xtype: MODx.expandHelp ? 'label' : 'hidden' 
                        ,forId: 'modx-snippet-name'
                        ,html: _('snippet_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('snippet_desc')
                        ,description: MODx.expandHelp ? '' : _('snippet_desc_description')
                        ,name: 'description'
                        ,id: 'modx-snippet-description'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-snippet-description'
                        ,html: _('snippet_desc_description')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-browser'
                        ,browserEl: 'modx-browser'
                        ,fieldLabel: _('static_file')
                        ,description: MODx.expandHelp ? '' : _('static_file_msg')
                        ,name: 'static_file'
                        ,hideFiles: true
                        ,openTo: config.record.openTo || ''
                        ,id: 'modx-snippet-static-file'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.static_file || ''
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-snippet-static-file'
                        ,id: 'modx-snippet-static-file-help'
                        ,html: _('static_file_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        html: MODx.onSnipFormRender
                        ,border: false
                    }]
                },{
                    columnWidth: .4
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('snippet_desc_category')
                        ,name: 'category'
                        ,id: 'modx-snippet-category'
                        ,anchor: '100%'
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden' 
                        ,forId: 'modx-snippet-category'
                        ,id: 'modx-snippet-category-help'
                        ,html: _('snippet_desc_category')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('snippet_lock')
                        ,description: _('snippet_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-snippet-locked'
                        ,inputValue: 1
                        ,checked: config.record.locked || 0
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('clear_cache_on_save')
                        ,description: _('clear_cache_on_save_msg')
                        ,hideLabel: true
                        ,name: 'clearCache'
                        ,id: 'modx-snippet-clear-cache'
                        ,inputValue: 1
                        ,checked: Ext.isDefined(config.record.clearCache) || true
                    },{
                        xtype: 'xcheckbox'
                        ,hideLabel: true
                        ,boxLabel: _('is_static')
                        ,description: MODx.expandHelp ? '' : _('is_static_msg')
                        ,name: 'static'
                        ,id: 'modx-snippet-static'
                        ,inputValue: 1
                        ,checked: config.record['static'] || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-snippet-static'
                        ,id: 'modx-snippet-static-help'
                        ,html: _('is_static_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-source'
                        ,fieldLabel: _('static_source')
                        ,description: MODx.expandHelp ? '' : _('static_source_msg')
                        ,name: 'source'
                        ,id: 'modx-snippet-static-source'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                        ,baseParams: {
                            action: 'getList'
                            ,showNone: true
                            ,streamsOnly: true
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-snippet-static-source'
                        ,id: 'modx-snippet-static-source-help'
                        ,html: _('static_source_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    }]
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
            ,record: config.record
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
    var isStatic = Ext.getCmp('modx-snippet-static');
    if (isStatic) { isStatic.on('check',this.toggleStaticFile); }
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized) { this.clearDirty(); return true; }
        this.getForm().setValues(this.config.record);
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
    ,toggleStaticFile: function(cb) {
        var flds = ['modx-snippet-static-file','modx-snippet-static-file-help','modx-snippet-static-source','modx-snippet-static-source-help'];
        var fld,i;
        if (cb.checked) {
            for (i in flds) {
                fld = Ext.getCmp(flds[i]);
                if (fld) { fld.show(); }
            }
        } else {
            for (i in flds) {
                fld = Ext.getCmp(flds[i]);
                if (fld) { fld.hide(); }
            }
        }
    }
});
Ext.reg('modx-panel-snippet',MODx.panel.Snippet);