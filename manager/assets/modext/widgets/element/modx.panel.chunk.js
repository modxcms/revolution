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
            action: 'Element/Chunk/Get'
        }
        ,id: 'modx-panel-chunk'
        ,cls: 'container form-with-labels'
        ,class_key: 'MODX\\Revolution\\modChunk'
        ,chunk: ''
        ,bodyStyle: ''
        ,previousFileSource: config.record.source != null ? config.record.source : MODx.config.default_media_source
        ,items: [{
            id: 'modx-chunk-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('general_information')
            ,layout: 'form'
            ,id: 'modx-chunk-form'
            ,labelWidth: 150
            ,defaults: {
                border: false
                ,layout: 'form'
				,labelAlign: 'top'
                ,labelSeparator: ''
                ,msgTarget: 'side'
            }
            ,items: [{
                html: '<p>'+_('chunk_tab_general_desc')+'</p>'
                ,id: 'modx-chunk-msg'
                ,xtype: 'modx-description'
            },{
                cls: 'main-wrapper'
                ,items: [{
                    // row 1
                    cls:'form-row-wrapper'
                    ,defaults: {
                        layout: 'column'
                    }
                    ,items: [{
                        defaults: {
                            layout: 'form'
                            ,labelSeparator: ''
                            ,labelAlign: 'top'
                        }
                        ,items: [{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
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
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('chunk_name_desc', {
                                    tag: `<span class="copy-this">[[$<span class="example-replace-name">${_('example_tag_chunk_name')}</span>]]</span>`
                                })
                                ,name: 'name'
                                ,id: 'modx-chunk-name'
                                ,maxLength: 50
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,value: config.record.name
                                ,tabIndex: 1
                                ,listeners: {
                                    keyup: {
                                        fn: function(cmp, e) {
                                            const   title = this.formatMainPanelTitle('chunk', this.config.record, cmp.getValue(), true),
                                                    tagTitle = title && title.length > 0 ? title : _('example_tag_chunk_name')
                                            ;
                                            cmp.nextSibling().getEl().child('.example-replace-name').update(tagTitle);
                                            MODx.setStaticElementPath('chunk');
                                        }
                                        ,scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-name'
                                ,html: _('chunk_name_desc', {
                                    tag: `<span class="copy-this">[[$<span class="example-replace-name">${_('example_tag_chunk_name')}</span>]]</span>`
                                })
                                ,cls: 'desc-under'
                                ,listeners: {
                                    afterrender: {
                                        fn: function(cmp) {
                                            this.insertTagCopyUtility(cmp, 'chunk');
                                        }
                                        ,scope: this
                                    }
                                }
                            }]
                        },{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                            }
                            ,items: [{
                                xtype: 'modx-combo-category'
                                ,fieldLabel: _('category')
                                ,description: MODx.expandHelp ? '' : _('chunk_category_desc')
                                ,name: 'category'
                                ,id: 'modx-chunk-category'
                                ,value: config.record.category || 0
                                ,tabIndex: 2
                                ,listeners: {
                                    afterrender: {scope:this,fn:function(f,e) {
                                        setTimeout(function(){
                                            MODx.setStaticElementPath('chunk');
                                        }, 200);
                                    }}
                                    ,change: {scope:this,fn:function(f,e) {
                                        MODx.setStaticElementPath('chunk');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-category'
                                ,html: _('chunk_category_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                },{
                    // row 2
                    cls:'form-row-wrapper'
                    ,defaults: {
                        layout: 'column'
                    }
                    ,items: [{
                        defaults: {
                            layout: 'form'
                            ,labelSeparator: ''
                            ,labelAlign: 'top'
                        }
                        ,items: [{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
                            ,items: [{
                                xtype: 'textarea'
                                ,fieldLabel: _('description')
                                ,description: MODx.expandHelp ? '' : _('chunk_description_desc')
                                ,name: 'description'
                                ,id: 'modx-chunk-description'
                                ,maxLength: 255
                                ,tabIndex: 5
                                ,value: config.record.description || ''
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-description'
                                ,html: _('chunk_description_desc')
                                ,cls: 'desc-under'
                            }]
                        },{
                            columnWidth: 0.5
                            ,cls: 'switch-container'
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
                            ,items: [{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('element_lock')
                                ,description: MODx.expandHelp ? '' : _('chunk_lock_desc')
                                ,name: 'locked'
                                ,id: 'modx-chunk-locked'
                                ,inputValue: 1
                                ,tabIndex: 6
                                ,checked: config.record.locked || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-locked'
                                ,html: _('chunk_lock_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('clear_cache_on_save')
                                ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_desc')
                                ,name: 'clearCache'
                                ,id: 'modx-chunk-clear-cache'
                                ,inputValue: 1
                                ,tabIndex: 7
                                ,checked: Ext.isDefined(config.record.clearCache) || true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-clear-cache'
                                ,html: _('clear_cache_on_save_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            }]
                        }]
                    }]
                },{
                    // row 3
                    cls:'form-row-wrapper'
                    ,defaults: {
                        layout: 'column'
                    }
                    ,items: [{
                        defaults: {
                            layout: 'form'
                            ,labelSeparator: ''
                            ,labelAlign: 'top'
                        }
                        ,items: [{
                            columnWidth: 1
                            ,cls: 'fs-toggle'
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
                            ,items: [{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('is_static')
                                ,description: MODx.expandHelp ? '' : _('is_static_desc')
                                ,name: 'static'
                                ,id: 'modx-chunk-static'
                                ,inputValue: 1
                                ,checked: config.record['static'] || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-static'
                                ,id: 'modx-chunk-static-help'
                                ,html: _('is_static_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            }]
                        }]
                    }]
                },{
                    // row 4
                    xtype: 'fieldset'
                    ,layout: 'form'
                    ,title: 'Static File Options'
                    ,autoHeight: true
                    ,cls: 'form-row-wrapper'
                    ,id: 'element-static-options-fs'
                    ,defaults: {
                        layout: 'column'
                    }
                    ,items: [{
                        defaults: {
                            layout: 'form'
                            ,labelSeparator: ''
                            ,labelAlign: 'top'
                        }
                        ,items: [{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                                ,hideMode: 'visibility'
                            }
                            ,items: [{
                                xtype: 'modx-combo-source'
                                ,fieldLabel: _('static_source')
                                ,description: MODx.expandHelp ? '' : _('static_source_desc')
                                ,name: 'source'
                                ,id: 'modx-chunk-static-source'
                                ,maxLength: 255
                                ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                                ,baseParams: {
                                    action: 'Source/GetList'
                                    ,showNone: true
                                    ,streamsOnly: true
                                }
                                ,listeners: {
                                    select: {
                                        fn: function(cmp, record, selectedIndex) {
                                            this.onChangeStaticSource(cmp, 'chunk');
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-static-source'
                                ,id: 'modx-chunk-static-source-help'
                                ,html: _('static_source_desc')
                                ,cls: 'desc-under'
                            }]
                        },{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                                ,hideMode: 'visibility'
                            }
                            ,items: [
                                this.getStaticFileField('chunk', config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-chunk-static-file'
                                ,id: 'modx-chunk-static-file-help'
                                ,html: _('static_file_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                    ,listeners: {
                        afterrender: function(cmp) {
                            const isStaticCmp = Ext.getCmp('modx-chunk-static');
                            if (isStaticCmp) {
                                this.isStatic = isStaticCmp.checked;
                                const   switchField = 'modx-chunk-static',
                                        toggleFields = ['modx-chunk-static-file','modx-chunk-static-source']
                                        ;
                                isStaticCmp.on('check', function(){
                                    this.toggleFieldVisibility(switchField, cmp.id, toggleFields);
                                }, this);
                                if(!this.isStatic) {
                                    this.toggleFieldVisibility(switchField, cmp.id, toggleFields);
                                }
                            }
                        }
                        ,scope: this
                    }
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
            ,elementType: 'MODX\\Revolution\\modChunk'
            ,record: config.record
        }],{
            id: 'modx-chunk-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,failureSubmit: {
                fn: function() {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-chunk-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Chunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Chunk,MODx.FormPanel,{

    initialized: false

    ,setup: function() {

        if (this.initialized) {
            this.clearDirty();
            return true;
        }
        /*
            The itemId (not id) of each form tab to be included/excluded; these correspond to the
            keys in each tab component's items property
        */
        this.errorHandlingTabs = ['modx-chunk-form'];
        this.errorHandlingIgnoreTabs = ['modx-panel-element-properties'];
        this.getForm().setValues(this.config.record);

        this.formatMainPanelTitle('chunk', this.config.record);
        this.getElementProperties(this.config.record.properties);
        this.fireEvent('ready',this.config.record);

        if (MODx.onLoadEditor) {
            MODx.onLoadEditor(this);
        }

        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
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

});
Ext.reg('modx-panel-chunk',MODx.panel.Chunk);
