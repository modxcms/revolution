/**
 * Loads the Template panel
 *
 * @class MODx.panel.Template
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-template
 */

MODx.panel.Template = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    config = MODx.setStaticElementsConfig(config, 'template');

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Template/Get'
        }
        ,id: 'modx-panel-template'
        ,cls: 'container form-with-labels'
        ,class_key: 'modTemplate'
        ,template: ''
        ,bodyStyle: ''
        ,previousFileSource: config.record.source != null ? config.record.source : MODx.config.default_media_source
        ,items: [{
            id: 'modx-template-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('general_information')
            ,layout: 'form'
            ,id: 'modx-template-form'
            ,labelWidth: 150
            ,defaults: {
                border: false
                ,layout: 'form'
				,labelAlign: 'top'
                ,labelSeparator: ''
                ,msgTarget: 'side'
            }
            ,items: [{
                html: '<p>'+_('template_tab_general_desc')+'</p>'
                ,id: 'modx-template-msg'
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
                                ,id: 'modx-template-id'
                                ,value: config.record.id || MODx.request.id
                            },{
                                xtype: 'hidden'
                                ,name: 'props'
                                ,id: 'modx-template-props'
                                ,value: config.record.props || null
                            },{
                                xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('template_name_desc')
                                ,name: 'templatename'
                                ,id: 'modx-template-templatename'
                                ,maxLength: 50
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,value: config.record.templatename
                                ,tabIndex: 1
                                ,listeners: {
                                    keyup: {
                                        fn: function(cmp, e) {
                                            this.formatMainPanelTitle('template', this.config.record, cmp.getValue());
                                            MODx.setStaticElementPath('template');
                                        }
                                        ,scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-templatename'
                                ,html: _('template_name_desc')
                                ,cls: 'desc-under'
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
                                ,description: MODx.expandHelp ? '' : _('template_category_desc')
                                ,name: 'category'
                                ,id: 'modx-template-category'
                                ,value: config.record.category || 0
                                ,tabIndex: 2
                                ,listeners: {
                                    afterrender: {scope:this,fn:function(f,e) {
                                        setTimeout(function(){
                                            MODx.setStaticElementPath('template');
                                        }, 200);
                                    }}
                                    ,change: {scope:this,fn:function(f,e) {
                                        MODx.setStaticElementPath('template');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-category'
                                ,html: _('template_category_desc')
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
                                ,description: MODx.expandHelp ? '' : _('template_description_desc')
                                ,name: 'description'
                                ,id: 'modx-template-description'
                                ,maxLength: 255
                                ,tabIndex: 5
                                ,value: config.record.description || ''
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-description'
                                ,html: _('template_description_desc')
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
                                ,description: MODx.expandHelp ? '' : _('template_lock_desc')
                                ,name: 'locked'
                                ,id: 'modx-template-locked'
                                ,inputValue: 1
                                ,tabIndex: 6
                                ,checked: config.record.locked || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-locked'
                                ,html: _('template_lock_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('clear_cache_on_save')
                                ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_desc')
                                ,name: 'clearCache'
                                ,id: 'modx-template-clear-cache'
                                ,inputValue: 1
                                ,tabIndex: 7
                                ,checked: Ext.isDefined(config.record.clearCache) || true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-clear-cache'
                                ,html: _('clear_cache_on_save_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            }]
                        }]
                    }]
                },{
                    // row 3 preview fields
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
                                xtype: 'modx-combo-source'
                                ,fieldLabel: _('template_preview_source')
                                ,description: MODx.expandHelp ? '' : _('template_preview_source_desc')
                                ,name: 'preview_source'
                                ,id: 'modx-template-preview-source'
                                ,maxLength: 255
                                ,submitValue: false
                                ,hiddenName: 'source'
                                ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                                ,baseParams: {
                                    action: 'Source/GetList'
                                    ,showNone: true
                                    ,streamsOnly: true
                                }
                                ,listeners: {
                                    select: {
                                        fn: function(cmp, record, selectedIndex) {
                                            this.onChangeStaticSource(cmp, 'template');
                                            Ext.getCmp('modx-template-static-source').setValue(cmp.getValue());
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-preview-source'
                                ,html: _('template_preview_source_desc')
                                ,cls: 'desc-under'
                            }]
                        },{
                            columnWidth: 0.5
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
                            ,items: [
                                this.getTemplatePreviewImageField(config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden',
                                forId: 'modx-template-preview-file',
                                html: _('template_preview_desc'),
                                cls: 'desc-under'
                            }]
                        }]
                    }]
                },{
                    // row 4 icon field
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
                            ,defaults: {
                                anchor: '100%'
                                ,msgTarget: 'under'
                                ,validationEvent: 'change'
                                ,validateOnBlur: false
                            }
                            ,items: [{
                                xtype: 'textfield'
                                ,fieldLabel: _('template_icon')
                                ,description: MODx.expandHelp ? '' : _('template_icon_desc')
                                ,name: 'icon'
                                ,id: 'modx-template-icon'
                                ,anchor: '100%'
                                ,maxLength: 100
                                ,enableKeyEvents: true
                                ,allowBlank: true
                                ,value: config.record.icon
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-icon'
                                ,html: _('template_icon_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                },{
                    // row 4
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
                                ,id: 'modx-template-static'
                                ,inputValue: 1
                                ,checked: config.record['static'] || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-static'
                                ,id: 'modx-template-static-help'
                                ,html: _('is_static_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            }]
                        }]
                    }]
                },{
                    // row 5
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
                                ,name: 'static_source'
                                ,id: 'modx-template-static-source'
                                ,maxLength: 255
                                ,submitValue: false
                                ,hiddenName: 'source'
                                ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                                ,baseParams: {
                                    action: 'Source/GetList'
                                    ,showNone: true
                                    ,streamsOnly: true
                                }
                                ,listeners: {
                                    select: {
                                        fn: function(cmp, record, selectedIndex) {
                                            this.onChangeStaticSource(cmp, 'template');
                                            Ext.getCmp('modx-template-preview-source').setValue(cmp.getValue());
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-static-source'
                                ,id: 'modx-template-static-source-help'
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
                                this.getStaticFileField('template', config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-template-static-file'
                                ,id: 'modx-template-static-file-help'
                                ,html: _('static_file_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                    ,listeners: {
                        afterrender: function(cmp) {
                            const isStaticCmp = Ext.getCmp('modx-template-static');
                            if (isStaticCmp) {
                                this.isStatic = isStaticCmp.checked;
                                const   switchField = 'modx-template-static',
                                        toggleFields = ['modx-template-static-file','modx-template-static-source']
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
                    ,fieldLabel: _('template_code')
                    ,name: 'content'
                    ,id: 'modx-template-content'
                    ,anchor: '100%'
                    ,height: 400
                    ,value: config.record.content || ''
                }]
            }]
        },{
            title: _('template_variables')
            ,itemId: 'form-template'
            ,defaults: {
                autoHeight: true
            }
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('template_tv_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-template-tv'
                ,cls:'main-wrapper'
                ,urlFilters: ['category', 'query']
                ,preventRender: true
                ,anchor: '100%'
                ,template: config.template
                ,listeners: {
                    rowclick: {fn:this.markDirty,scope:this}
                    ,afterEdit: {fn:this.markDirty,scope:this}
                    ,afterRemoveRow: {fn:this.markDirty,scope:this}
                }
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,preventRender: true
            ,collapsible: true
            ,elementPanel: 'modx-panel-template'
            ,elementId: config.template
            ,elementType: 'MODX\\Revolution\\modTemplate'
            ,record: config.record
        }],{
            id: 'modx-template-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,failureSubmit: {
                fn: function() {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-template-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{

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
        this.errorHandlingTabs = ['modx-template-form'];
        this.errorHandlingIgnoreTabs = ['modx-panel-element-properties','form-template'];
        this.getForm().setValues(this.config.record);

        this.formatMainPanelTitle('template', this.config.record);
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

        var t = Ext.getCmp('modx-tree-element');
        if (t) {
            var c = Ext.getCmp('modx-template-category').getValue();
            var u = c != '' && c != null && c != 0 ? 'n_template_category_'+c : 'n_type_template';
            var node = t.getNodeById('n_template_element_' + Ext.getCmp('modx-template-id').getValue() + '_' + r.result.object.previous_category);
            if (node) node.destroy();
            t.refreshNode(u,true);
        }
    }

    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-template-which-editor').getValue();
            MODx.request.a = 'element/template/update';
            location.href = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
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
