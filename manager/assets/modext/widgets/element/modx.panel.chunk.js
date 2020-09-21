/**
 * @class MODx.panel.Chunk
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-chunk
 */
MODx.panel.Chunk = function(config) {
    config = config || {};
    config.record = config.record || {};
    config = MODx.setStaticElementsConfig(config, 'chunk');

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/chunk/get'
        }
        ,id: 'modx-panel-chunk'
		,cls: 'container form-with-labels'
        ,class_key: 'modChunk'
        ,chunk: ''
        ,bodyStyle: ''
        ,items: [{
            html: _('chunk_new')
            ,id: 'modx-chunk-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('chunk_title')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-chunk-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('chunk_msg')+'</p>'
                ,id: 'modx-chunk-msg'
                ,xtype: 'modx-description'
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
                    ,defaults: {
                        msgTarget: 'under'
                        ,validationEvent: 'change'
                        ,validateOnBlur: false
                    }
                }
                ,items: [{
                    columnWidth: .6
                    ,items: [{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'modx-chunk-id'
                        ,value: config.record.id || MODx.request.id
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-chunk-props'
                        ,value: config.record.props || null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')+'<span class="required">*</span>'
                        ,description: MODx.expandHelp ? '' : _('chunk_desc_name')
                        ,name: 'name'
                        ,id: 'modx-chunk-name'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,value: config.record.name
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                var title = Ext.util.Format.stripTags(f.getValue());
                                title = _('chunk')+': '+Ext.util.Format.htmlEncode(title);
                                if (MODx.request.a !== 'element/chunk/create' && MODx.perm.tree_show_element_ids === 1) {
                                    title += ' <small>('+this.config.record.id+')</small>';
                                }

                                Ext.getCmp('modx-chunk-header').getEl().update(title);

                                MODx.setStaticElementPath('chunk');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-name'
                        ,html: _('chunk_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('description')
                        ,description: MODx.expandHelp ? '' : _('chunk_desc_description')
                        ,name: 'description'
                        ,id: 'modx-chunk-description'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.description
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-description'
                        ,html: _('chunk_desc_description')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-browser'
                        ,browserEl: 'modx-browser'
                        ,fieldLabel: _('static_file')
                        ,description: MODx.expandHelp ? '' : _('static_file_msg')
                        ,name: 'static_file'
                        // ,hideFiles: true
                        ,source: config.record.source != null ? config.record.source : MODx.config.default_media_source
                        ,openTo: config.record.openTo || ''
                        ,id: 'modx-chunk-static-file'
                        ,triggerClass: 'x-form-code-trigger'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.static_file || ''
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                        ,validator: function(value){
                            if (Ext.getCmp('modx-chunk-static').getValue() === true) {
                                if (Ext.util.Format.trim(value) != '') {
                                    return true;
                                } else {
                                    return _('static_file_ns');
                                }
                            }

                            return true;
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-static-file'
                        ,id: 'modx-chunk-static-file-help'
                        ,html: _('static_file_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        html: MODx.onChunkFormRender
                        ,border: false
                    }]
                },{
                    columnWidth: .4
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('chunk_desc_category')
                        ,name: 'category'
                        ,id: 'modx-chunk-category'
                        ,anchor: '100%'
                        ,value: config.record.category || 0
                        ,listeners: {
                            'afterrender': {scope:this,fn:function(f,e) {
                                setTimeout(function(){
                                    MODx.setStaticElementPath('chunk');
                                }, 200);
                            }}
                            ,'change': {scope:this,fn:function(f,e) {
                                MODx.setStaticElementPath('chunk');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-category'
                        ,html: _('chunk_desc_category')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('chunk_lock')
                        ,description: MODx.expandHelp ? '' : _('chunk_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-chunk-locked'
                        ,inputValue: true
                        ,checked: config.record.locked || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-locked'
                        ,html: _('chunk_lock_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('clear_cache_on_save')
                        ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_msg')
                        ,hideLabel: true
                        ,name: 'clearCache'
                        ,id: 'modx-chunk-clear-cache'
                        ,inputValue: 1
                        ,checked: Ext.isDefined(config.record.clearCache) || true

                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-clear-cache'
                        ,html: _('clear_cache_on_save_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,hideLabel: true
                        ,boxLabel: _('is_static')
                        ,description: MODx.expandHelp ? '' : _('is_static_msg')
                        ,name: 'static'
                        ,id: 'modx-chunk-static'
                        ,inputValue: 1
                        ,checked: config.record['static'] || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-static'
                        ,id: 'modx-chunk-static-help'
                        ,html: _('is_static_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-source'
                        ,fieldLabel: _('static_source')
                        ,description: MODx.expandHelp ? '' : _('static_source_msg')
                        ,name: 'source'
                        ,id: 'modx-chunk-static-source'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                        ,baseParams: {
                            action: 'source/getList'
                            ,showNone: true
                            ,streamsOnly: true
                        }
                        ,listeners: {
                            select: {
                                fn: this.changeSource
                                ,scope: this
                            }
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-chunk-static-source'
                        ,id: 'modx-chunk-static-source-help'
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
					,fieldLabel: _('chunk_code')
					,name: 'snippet'
					,id: 'modx-chunk-snippet'
					,anchor: '100%'
					,height: 400
					,value: config.record.snippet || ''
				}]
			}]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-chunk'
            ,elementId: config.chunk
            ,elementType: 'modChunk'
            ,record: config.record
        }],{
            id: 'modx-chunk-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'failure': {fn:this.failure,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'failureSubmit': {
                fn: function () {
                    this.showErroredTab(['modx-chunk-form'], 'modx-chunk-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Chunk.superclass.constructor.call(this,config);

    var isStatic = Ext.getCmp('modx-chunk-static');
    if (isStatic) { isStatic.on('check',this.toggleStaticFile); }
};
Ext.extend(MODx.panel.Chunk,MODx.FormPanel,{
    initialized: false
    ,setup: function() {

        if (!this.initialized) {
            /*
                The itemId (not id) of each form tab to be included/excluded; these correspond to the
                keys in each tab component's items property
            */
            this.errorHandlingTabs = ['modx-chunk-form'];
            this.errorHandlingIgnoreTabs = ['modx-panel-element-properties'];

            this.getForm().setValues(this.config.record);
        }

        if (this.initialized) { this.clearDirty(); return true; }

        if (!Ext.isEmpty(this.config.record.name)) {
            var title = _('chunk')+': '+this.config.record.name;
            if (MODx.perm.tree_show_element_ids === 1) {
                title = title+ ' <small>('+this.config.record.id+')</small>';
            }
            Ext.getCmp('modx-chunk-header').getEl().update(title);
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
        MODx.fireEvent('ready');
        return true;
    }

    /**
     * Set the browser window "media source" source
     */
    ,changeSource: function() {
        var browser = Ext.getCmp('modx-chunk-static-file')
            ,source = Ext.getCmp('modx-chunk-static-source').getValue();

        browser.config.source = source;
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

        var c = Ext.getCmp('modx-chunk-category').getValue();
        var n = c !== '' && c !== null && c != 0 ? 'n_chunk_category_'+c : 'n_type_chunk';
        var t = Ext.getCmp('modx-tree-element');
        if (t) {
        	var node = t.getNodeById('n_chunk_element_' + Ext.getCmp('modx-chunk-id').getValue() + '_' + r.result.object.previous_category);
        	if (node) node.destroy();
        	t.refreshNode(n,true);
        }
    }

    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-chunk-snippet');
            MODx.onSaveEditor(fld);
        }
    }
    ,toggleStaticFile: function(cb) {
        var flds = ['modx-chunk-static-file','modx-chunk-static-file-help','modx-chunk-static-source','modx-chunk-static-source-help'];
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
Ext.reg('modx-panel-chunk',MODx.panel.Chunk);
