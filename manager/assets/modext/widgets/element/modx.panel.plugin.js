/**
 *
 * @class MODx.panel.Plugin
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-plugin
 */
MODx.panel.Plugin = function(config) {
    config = config || {};
    config.record = config.record || {};
    config = MODx.setStaticElementsConfig(config, 'plugin');

    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Element/Plugin/Get'
        }
        ,id: 'modx-panel-plugin'
		,cls: 'container form-with-labels'
        ,class_key: 'MODX\\Revolution\\modPlugin'
        ,plugin: ''
        ,bodyStyle: ''
        ,allowDrop: false
        ,previousFileSource: config.record.source != null ? config.record.source : MODx.config.default_media_source
        ,items: [{
            id: 'modx-plugin-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('general_information')
            ,layout: 'form'
            ,id: 'modx-plugin-form'
            ,labelWidth: 150
            ,defaults: {
                border: false
                ,layout: 'form'
				,labelAlign: 'top'
                ,labelSeparator: ''
                ,msgTarget: 'side'
            }
            ,items: [{
                html: '<p>'+_('plugin_tab_general_desc')+'</p>'
                ,id: 'modx-plugin-msg'
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
                                ,id: 'modx-plugin-id'
                                ,value: config.record.id || MODx.request.id
                            },{
                                xtype: 'hidden'
                                ,name: 'props'
                                ,id: 'modx-plugin-props'
                                ,value: config.record.props || null
                            },{
                                xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('plugin_name_desc')
                                ,name: 'name'
                                ,id: 'modx-plugin-name'
                                ,maxLength: 50
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,value: config.record.name
                                ,tabIndex: 1
                                ,listeners: {
                                    keyup: {
                                        fn: function(cmp, e) {
                                            this.formatMainPanelTitle('plugin', this.config.record, cmp.getValue());
                                            MODx.setStaticElementPath('plugin');
                                        }
                                        ,scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-name'
                                ,html: _('plugin_name_desc')
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
                                ,description: MODx.expandHelp ? '' : _('plugin_category_desc')
                                ,name: 'category'
                                ,id: 'modx-plugin-category'
                                ,value: config.record.category || 0
                                ,tabIndex: 2
                                ,listeners: {
                                    afterrender: {scope:this,fn:function(f,e) {
                                        setTimeout(function(){
                                            MODx.setStaticElementPath('plugin');
                                        }, 200);
                                    }}
                                    ,change: {scope:this,fn:function(f,e) {
                                        MODx.setStaticElementPath('plugin');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-category'
                                ,html: _('plugin_category_desc')
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
                                ,description: MODx.expandHelp ? '' : _('plugin_description_desc')
                                ,name: 'description'
                                ,id: 'modx-plugin-description'
                                ,maxLength: 255
                                ,tabIndex: 3
                                ,value: config.record.description || ''
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-description'
                                ,html: _('plugin_description_desc')
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
                                ,description: MODx.expandHelp ? '' : _('plugin_disabled_msg')
                                ,boxLabel: _('plugin_disabled')
                                ,name: 'disabled'
                                ,id: 'modx-plugin-disabled'
                                ,inputValue: 1
                                ,tabIndex: 4
                                ,checked: config.record.disabled || 0
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-disabled'
                                ,html: _('plugin_disabled_msg')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('element_lock')
                                ,description: MODx.expandHelp ? '' : _('plugin_lock_desc')
                                ,name: 'locked'
                                ,id: 'modx-plugin-locked'
                                ,inputValue: 1
                                ,tabIndex: 5
                                ,checked: config.record.locked || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-locked'
                                ,html: _('plugin_lock_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('clear_cache_on_save')
                                ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_desc')
                                ,name: 'clearCache'
                                ,id: 'modx-plugin-clear-cache'
                                ,inputValue: 1
                                ,tabIndex: 6
                                ,checked: Ext.isDefined(config.record.clearCache) || true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-clear-cache'
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
                                ,id: 'modx-plugin-static'
                                ,inputValue: 1
                                ,tabIndex: 7
                                ,checked: config.record['static'] || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-static'
                                ,id: 'modx-plugin-static-help'
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
                                ,id: 'modx-plugin-static-source'
                                ,maxLength: 255
                                ,value: config.record.source != null ? config.record.source : MODx.config.default_media_source
                                ,tabIndex: 8
                                ,baseParams: {
                                    action: 'Source/GetList'
                                    ,showNone: true
                                    ,streamsOnly: true
                                }
                                ,listeners: {
                                    select: {
                                        fn: function(cmp, record, selectedIndex) {
                                            this.onChangeStaticSource(cmp, 'plugin');
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-static-source'
                                ,id: 'modx-plugin-static-source-help'
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
                                this.getStaticFileField('plugin', config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-plugin-static-file'
                                ,id: 'modx-plugin-static-file-help'
                                ,html: _('static_file_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                    ,listeners: {
                        afterrender: function(cmp) {
                            const isStaticCmp = Ext.getCmp('modx-plugin-static');
                            if (isStaticCmp) {
                                this.isStatic = isStaticCmp.checked;
                                const   switchField = 'modx-plugin-static',
                                        toggleFields = ['modx-plugin-static-file','modx-plugin-static-source']
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
				,cls:'main-wrapper'
				,items: [{
					xtype: 'textarea'
					,fieldLabel: _('plugin_code')
					,name: 'plugincode'
					,id: 'modx-plugin-plugincode'
					,anchor: '100%'
					,height: 400
					,value: config.record.plugincode || "<?php\n"
                    ,tabIndex: 10
                }]
            }]
        },{
            title: _('system_events')
            ,id: 'modx-plugin-sysevents'
            ,items: [{
                html: '<p>'+_('plugin_event_msg')+'</p>'
                ,id: 'modx-plugin-sysevents-msg'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-plugin-event'
				,cls:'main-wrapper'
                ,preventRender: true
                ,plugin: config.record.id || 0
                ,listeners: {
                    updateEvent: {fn:this.markDirty,scope:this}
                    ,rowclick: {fn:this.markDirty,scope:this}
                }
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-plugin'
            ,elementId: config.plugin
            ,elementType: 'MODX\\Revolution\\modPlugin'
            ,record: config.record
        }],{
            id: 'modx-plugin-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,failureSubmit: {
                fn: function () {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-plugin-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Plugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Plugin,MODx.FormPanel,{

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
        this.errorHandlingTabs = ['modx-plugin-form'];
        this.errorHandlingIgnoreTabs = ['modx-plugin-sysevents','modx-panel-element-properties'];
        this.getForm().setValues(this.config.record);

        this.formatMainPanelTitle('plugin', this.config.record);
        this.getElementProperties(this.config.record.properties);
        this.fireEvent('ready',this.config.record);

        if (MODx.onLoadEditor) {
            MODx.onLoadEditor(this);
        }

        this.clearDirty();
        MODx.fireEvent('ready');
        this.initialized = true;
    }

    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-plugin-event');
        Ext.apply(o.form.baseParams,{
            events: g.encodeModified()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }

    ,success: function(o) {
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        Ext.getCmp('modx-grid-plugin-event').getStore().commitChanges();
        this.getForm().setValues(o.result.object);

        var t = Ext.getCmp('modx-tree-element');
        if (t) {
            var c = Ext.getCmp('modx-plugin-category').getValue();
            var u = c != '' && c != null && c != 0 ? 'n_plugin_category_'+c : 'n_type_plugin';
            var node = t.getNodeById('n_plugin_element_' + Ext.getCmp('modx-plugin-id').getValue() + '_' + o.result.object.previous_category);
            if (node) node.destroy();
            t.refreshNode(u,true);
        }
    }

    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-plugin-which-editor').getValue();
            MODx.request.a = 'element/plugin/update';
            location.href = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
        });
        this.submit();
    }

    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-plugin-plugincode');
            MODx.onSaveEditor(fld);
        }
    }

});
Ext.reg('modx-panel-plugin',MODx.panel.Plugin);
