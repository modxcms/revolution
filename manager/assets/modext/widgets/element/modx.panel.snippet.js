/**
 * @class MODx.panel.Snippet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-snippet
 */
MODx.panel.Snippet = function(config) {
    config = config || {};
    config.record = config.record || {};
    config = MODx.setStaticElementsConfig(config, 'snippet');

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Snippet/Get'
        }
        ,id: 'modx-panel-snippet'
		,cls: 'container form-with-labels'
        ,class_key: 'modSnippet'
        ,plugin: ''
        ,bodyStyle: ''
        ,allowDrop: false
        ,previousFileSource: config.record.source != null ? config.record.source : MODx.config.default_media_source
        ,items: [{
            html: _('snippet_new')
            ,id: 'modx-snippet-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('general_information')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-snippet-form'
            ,labelWidth: 150
            ,defaults: {
                border: false
                ,layout: 'form'
				,labelAlign: 'top'
                ,labelSeparator: ''
                ,msgTarget: 'side'
            }
            ,items: [{
                html: '<p>'+_('snippet_tab_general_desc')+'</p>'
                ,id: 'modx-snippet-msg'
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
                                ,id: 'modx-snippet-id'
                                ,value: config.record.id || MODx.request.id
                            },{
                                xtype: 'hidden'
                                ,name: 'props'
                                ,id: 'modx-snippet-props'
                                ,value: config.record.props || null
                            },{
                                xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('snippet_name_desc', {
                                    tag: `<span class="copy-this">[[<span class="example-replace-name">${_('example_tag_snippet_name')}</span>]]</span>`
                                })
                                ,name: 'name'
                                ,id: 'modx-snippet-name'
                                ,maxLength: 50
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,value: config.record.name
                                ,tabIndex: 1
                                ,listeners: {
                                    keyup: {
                                        fn: function(cmp, e) {
                                            const   title = this.formatMainPanelTitle('snippet', this.config.record, cmp.getValue(), true),
                                                    tagTitle = title && title.length > 0 ? title : _('example_tag_snippet_name')
                                            ;
                                            cmp.nextSibling().getEl().child('.example-replace-name').update(tagTitle);
                                            MODx.setStaticElementPath('snippet');
                                        }
                                        ,scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-name'
                                ,html: _('snippet_name_desc', {
                                    tag: `<span class="copy-this">[[<span class="example-replace-name">${_('example_tag_snippet_name')}</span>]]</span>`
                                })
                                ,cls: 'desc-under'
                                ,listeners: {
                                    afterrender: {
                                        fn: function(cmp) {
                                            this.insertTagCopyUtility(cmp, 'snippet');
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
                                ,description: MODx.expandHelp ? '' : _('snippet_category_desc')
                                ,name: 'category'
                                ,id: 'modx-snippet-category'
                                ,value: config.record.category || 0
                                ,tabIndex: 2
                                ,listeners: {
                                    afterrender: {scope:this,fn:function(f,e) {
                                        setTimeout(function(){
                                            MODx.setStaticElementPath('snippet');
                                        }, 200);
                                    }}
                                    ,change: {scope:this,fn:function(f,e) {
                                        MODx.setStaticElementPath('snippet');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-category'
                                ,html: _('snippet_category_desc')
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
                                ,description: MODx.expandHelp ? '' : _('snippet_description_desc')
                                ,name: 'description'
                                ,id: 'modx-snippet-description'
                                ,maxLength: 255
                                ,tabIndex: 3
                                ,value: config.record.description || ''
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-description'
                                ,html: _('snippet_description_desc')
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
                                ,description: MODx.expandHelp ? '' : _('snippet_lock_desc')
                                ,name: 'locked'
                                ,id: 'modx-snippet-locked'
                                ,inputValue: 1
                                ,tabIndex: 4
                                ,checked: config.record.locked || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-locked'
                                ,html: _('snippet_lock_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('clear_cache_on_save')
                                ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_desc')
                                ,name: 'clearCache'
                                ,id: 'modx-snippet-clear-cache'
                                ,inputValue: 1
                                ,tabIndex: 5
                                ,checked: Ext.isDefined(config.record.clearCache) || true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-clear-cache'
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
                                ,id: 'modx-snippet-static'
                                ,inputValue: 1
                                ,tabIndex: 6
                                ,checked: config.record['static'] || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-static'
                                ,id: 'modx-snippet-static-help'
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
                                ,id: 'modx-snippet-static-source'
                                ,maxLength: 255
                                ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                                ,tabIndex: 7
                                ,baseParams: {
                                    action: 'Source/GetList'
                                    ,showNone: true
                                    ,streamsOnly: true
                                }
                                ,listeners: {
                                    select: {
                                        fn: function(cmp, record, selectedIndex) {
                                            this.onChangeStaticSource(cmp, 'snippet');
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-static-source'
                                ,id: 'modx-snippet-static-source-help'
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
                                this.getStaticFileField('snippet', config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-snippet-static-file'
                                ,id: 'modx-snippet-static-file-help'
                                ,html: _('static_file_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                    ,listeners: {
                        afterrender: function(cmp) {
                            const isStaticCmp = Ext.getCmp('modx-snippet-static');
                            if (isStaticCmp) {
                                this.isStatic = isStaticCmp.checked;
                                const   switchField = 'modx-snippet-static',
                                        toggleFields = ['modx-snippet-static-file','modx-snippet-static-source']
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
					,fieldLabel: _('snippet_code')
					,name: 'snippet'
					,id: 'modx-snippet-snippet'
					,anchor: '100%'
					,height: 400
					,value: config.record.snippet || "<?php\n"
                    ,tabIndex: 9
                }]
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-snippet'
            ,elementId: config.snippet
            ,elementType: 'MODX\\Revolution\\modSnippet'
            ,record: config.record
        }],{
            id: 'modx-snippet-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,failureSubmit: {
                fn: function() {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-snippet-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{

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
        this.errorHandlingTabs = ['modx-snippet-form'];
        this.errorHandlingIgnoreTabs = ['modx-panel-element-properties'];
        this.getForm().setValues(this.config.record);

        this.formatMainPanelTitle('snippet', this.config.record);
        this.getElementProperties(this.config.record.properties);
        this.fireEvent('ready',this.config.record);

        if (MODx.onLoadEditor) {
            MODx.onLoadEditor(this);
        }

        this.clearDirty();
        this.initialized = true;
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
        if (MODx.request.id) {
            Ext.getCmp('modx-grid-element-properties').save();
        }
        this.getForm().setValues(r.result.object);

        var t = Ext.getCmp('modx-tree-element');
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
            MODx.request.a = 'element/snippet/update';
            location.href = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
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
