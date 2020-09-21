/**
 * Loads the TV panel
 *
 * @class MODx.panel.TV
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-tv
 */
MODx.panel.TV = function(config) {
    config = config || {};
    config.record = config.record || {};
    config = MODx.setStaticElementsConfig(config, 'tv');
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/tv/get'
        }
        ,id: 'modx-panel-tv'
		,cls: 'container form-with-labels'
        ,class_key: 'modTemplateVar'
        ,tv: ''
        ,bodyStyle: ''
        ,items: [{
            html: _('tv_new')
            ,id: 'modx-tv-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('general_information')
            ,defaults: {
                border: false
                ,msgTarget: 'side'
                ,layout: 'form'
            }
            ,layout: 'form'
            ,id: 'modx-tv-form'
            ,itemId: 'form-tv'
            ,labelWidth: 150
            ,forceLayout: true
            ,items: [{
                html: '<p>'+_('tv_msg')+'</p>'
                ,id: 'modx-tv-msg'
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
                        ,id: 'modx-tv-id'
                        ,value: config.record.id || MODx.request.id
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-tv-props'
                        ,value: config.record.props || null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')+'<span class="required">*</span>'
                        ,description: MODx.expandHelp ? '' : _('tv_desc_name')
                        ,name: 'name'
                        ,id: 'modx-tv-name'
                        ,anchor: '100%'
                        ,maxLength: 100
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,value: config.record.name
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                var title = Ext.util.Format.stripTags(f.getValue());
                                title = _('tv')+': '+Ext.util.Format.htmlEncode(title);
                                if (MODx.request.a !== 'element/tv/create' && MODx.perm.tree_show_element_ids === 1) {
                                    title = title+ ' <small>('+this.config.record.id+')</small>';
                                }

                                Ext.getCmp('modx-tv-header').getEl().update(title);

                                MODx.setStaticElementPath('tv');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-name'
                        ,html: _('tv_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('tv_caption')
                        ,description: MODx.expandHelp ? '' : _('tv_desc_caption')
                        ,name: 'caption'
                        ,id: 'modx-tv-caption'
                        ,anchor: '100%'
                        ,value: config.record.caption
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-caption'
                        ,html: _('tv_desc_caption')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('description')
                        ,description: MODx.expandHelp ? '' : _('tv_desc_description')
                        ,name: 'description'
                        ,id: 'modx-tv-description'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-description'
                        ,html: _('tv_desc_description')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-browser'
                        ,browserEl: 'modx-browser'
                        ,fieldLabel: _('static_file')
                        ,description: MODx.expandHelp ? '' : _('static_file_msg')
                        ,name: 'static_file'
                        // ,hideFiles: true
                        ,openTo: config.record.openTo || ''
                        ,id: 'modx-tv-static-file'
                        ,triggerClass: 'x-form-code-trigger'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.static_file || ''
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-static-file'
                        ,id: 'modx-tv-static-file-help'
                        ,html: _('static_file_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        html: MODx.onTVFormRender
                        ,border: false
                    }]
                },{
                    columnWidth: .4
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,name: 'category'
                        ,id: 'modx-tv-category'
                        ,anchor: '100%'
                        ,value: config.record.category || 0
                        ,listeners: {
                            'afterrender': {scope:this,fn:function(f,e) {
                                setTimeout(function(){
                                    MODx.setStaticElementPath('tv');
                                }, 200);
                            }}
                            ,'change': {scope:this,fn:function(f,e) {
                                MODx.setStaticElementPath('tv');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-category'
                        ,html: _('tv_desc_category')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'numberfield'
                        ,fieldLabel: _('tv_rank')
                        ,name: 'rank'
                        ,id: 'modx-tv-rank'
                        ,width: 50
                        ,maxLength: 4
                        ,allowNegative: false
                        ,allowBlank: false
                        ,value: config.record.rank || 0
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('tv_lock')
                        ,description: MODx.expandHelp ? '' : _('tv_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-tv-locked'
                        ,inputValue: 1
                        ,checked: config.record.locked || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-locked'
                        ,id: 'modx-tv-locked-help'
                        ,html: _('tv_lock_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,hideLabel: true
                        ,boxLabel: _('clear_cache_on_save')
                        ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_msg')
                        ,name: 'clearCache'
                        ,id: 'modx-tv-clear-cache'
                        ,inputValue: 1
                        ,checked: Ext.isDefined(config.record.clearCache) || true
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-clear-cache'
                        ,id: 'modx-tv-clear-cache-help'
                        ,html: _('clear_cache_on_save_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,hideLabel: true
                        ,boxLabel: _('is_static')
                        ,description: MODx.expandHelp ? '' : _('is_static_msg')
                        ,name: 'static'
                        ,id: 'modx-tv-static'
                        ,inputValue: 1
                        ,checked: config.record['static'] || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-tv-static'
                        ,id: 'modx-tv-static-help'
                        ,html: _('is_static_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-source'
                        ,fieldLabel: _('static_source')
                        ,description: MODx.expandHelp ? '' : _('static_source_msg')
                        ,name: 'source'
                        ,id: 'modx-tv-static-source'
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
                        ,forId: 'modx-tv-static-source'
                        ,id: 'modx-tv-static-source-help'
                        ,html: _('static_source_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    }]

                }]
			}]
        },{
            xtype: 'modx-panel-element-properties'
            ,itemId: 'panel-properties'
            ,elementPanel: 'modx-panel-tv'
            ,elementId: config.tv
            ,elementType: 'modTemplateVar'
            ,record: config.record
        },{
            xtype: 'modx-panel-tv-input-properties'
            ,record: config.record
        },{
            xtype: 'modx-panel-tv-output-properties'
            ,record: config.record
        },{
            title: _('tv_tmpl_access')
            ,itemId: 'form-template'
            ,hideMode: 'offsets'
            ,defaults: {autoHeight: true}
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('tv_tmpl_access_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-tv-template'
                ,itemId: 'grid-template'
				,cls:'main-wrapper'
                ,tv: config.tv
                ,preventRender: true
                ,anchor: '100%'
                ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afteredit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
                }
            }]
        },{
            title: _('access_permissions')
            ,id: 'modx-tv-access-form'
            ,itemId: 'form-access'
            ,forceLayout: true
            ,hideMode: 'offsets'
            ,defaults: {autoHeight: true}
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('tv_access_msg')+'</p>'
                ,id: 'modx-tv-access-msg'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-tv-security'
                ,itemId: 'grid-access'
                ,cls:'main-wrapper'
                ,tv: config.tv
                ,preventRender: true
                ,anchor: '100%'
                ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afteredit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
                }
            }]
        },{
            title: _('sources')
            ,id: 'modx-tv-sources-form'
            ,itemId: 'form-sources'
            ,defaults: {autoHeight: true}
			,layout: 'form'
			,hideMode: 'offsets'
            ,items: [{
                html: '<p>'+_('tv_sources.intro_msg')+'</p>'
                ,id: 'modx-tv-sources-msg'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-element-sources'
                ,itemId: 'grid-sources'
				,cls:'main-wrapper'
                ,id: 'modx-grid-element-sources'
                ,tv: config.tv
                ,preventRender: true
                ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afteredit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
                }
            }]
        }],{
            id: 'modx-tv-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-tv-tabpanel-'+config.tv
            ,stateEvents: ['tabchange']
            ,hideMode: 'offsets'
            ,anchor: '100%'
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'failure': {fn:this.failure,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'failureSubmit': {
                fn: function () {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-tv-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
    var isStatic = Ext.getCmp('modx-tv-static');
    if (isStatic) { isStatic.on('check',this.toggleStaticFile); }
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{
    initialized: false

    ,setup: function() {

        if (!this.initialized) {
            /*
                The itemId (not id) of each form tab to be included/excluded; these correspond to the
                keys in each tab component's items property
            */
            this.errorHandlingTabs = ['form-tv','modx-panel-tv-input-properties','modx-panel-tv-output-properties'];
            this.errorHandlingIgnoreTabs = ['panel-properties','form-template','form-access','form-sources'];

            this.getForm().setValues(this.config.record);
        }

        if (this.initialized) { this.clearDirty(); return true; }

        if (!Ext.isEmpty(this.config.record.name)) {
            var title = _('tv')+': '+this.config.record.name;
            if (MODx.perm.tree_show_element_ids === 1) {
                title = title+ ' <small>('+this.config.record.id+')</small>';
            }
            Ext.getCmp('modx-tv-header').getEl().update(title);
        }
        var d;
        if (!Ext.isEmpty(this.config.record.properties)) {
            d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }

        if (!Ext.isEmpty(this.config.record.sources) && !this.initialized) {
            Ext.getCmp('modx-grid-element-sources').getStore().loadData(this.config.record.sources);
        }

        Ext.getCmp('modx-panel-tv-output-properties').showOutputProperties(Ext.getCmp('modx-tv-display'));
        Ext.getCmp('modx-panel-tv-input-properties').showInputProperties(Ext.getCmp('modx-tv-type'));

        this.fireEvent('ready',this.config.record);
        if (MODx.onLoadEditor) {MODx.onLoadEditor(this);}
        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
        return true;
    }

    /**
     * Set the browser window "media source" source
     */
    ,changeSource: function() {
        var browser = Ext.getCmp('modx-tv-static-file')
            ,source = Ext.getCmp('modx-tv-static-source').getValue();

        browser.config.source = source;
    }

    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-tv-template');
        var rg = Ext.getCmp('modx-grid-tv-security');
        var sg = Ext.getCmp('modx-grid-element-sources');
        Ext.apply(o.form.baseParams,{
            templates: g.encodeModified()
            ,resource_groups: rg.encodeModified()
            ,sources: sg.encode()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }

    ,success: function(r) {
        Ext.getCmp('modx-grid-tv-template').getStore().commitChanges();
        Ext.getCmp('modx-grid-tv-security').getStore().commitChanges();
        Ext.getCmp('modx-grid-element-sources').getStore().commitChanges();
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        this.getForm().setValues(r.result.object);

        var t = Ext.getCmp('modx-tree-element');
        if (t) {
            var c = Ext.getCmp('modx-tv-category').getValue();
            var u = c != '' && c != null && c != 0 ? 'n_tv_category_'+c : 'n_type_tv';
            var node = t.getNodeById('n_tv_element_' + Ext.getCmp('modx-tv-id').getValue() + '_' + r.result.object.previous_category);
            if (node) node.destroy();
            t.refreshNode(u,true);
        }
    }

    ,changeEditor: function() {
        this.cleanupEditor();
        this.submit();
    }

    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-tv-default-text');
            MODx.onSaveEditor(fld);
        }
    }

    ,toggleStaticFile: function(cb) {
        var flds = ['modx-tv-static-file','modx-tv-static-file-help','modx-tv-static-source','modx-tv-static-source-help'];
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

    /*
        NOTE: The validatorRefMap object provides an easy way to map differently named fields to a
        streamlined validator method, by tv type, without the need to pass in variables to the target method
        from the field item's validator config
    */
    ,validatorRefMap: {
        text: {
            minLtMax: {
                compareTo: "inopt_maxLength",
                errMsg: _("ext_minlenmaxfield")
            },
            maxGtMin: {
                compareTo: "inopt_minLength",
                errMsg: _("ext_maxlenminfield")
            }
        },
        email: {
            minLtMax: {
                compareTo: "inopt_maxLength",
                errMsg: _("ext_minlenmaxfield")
            },
            maxGtMin: {
                compareTo: "inopt_minLength",
                errMsg: _("ext_maxlenminfield")
            }
        },
        number: {
            minLtMax: {
                compareTo: "inopt_maxValue",
                errMsg: _("ext_minvalmaxfield")
            },
            maxGtMin: {
                compareTo: "inopt_minValue",
                errMsg: _("ext_maxvalminfield")
            }
        }
    }

    ,tvOptsValidators: function(tvId, type) {
        // Here, 'this' refers to the current class (MODx.panel.TV)
        const me = this;

        // Within each validator, 'this' refers to the input element triggering the given method
        let ov = {
            minLtMax: function (v) {
                const maxFld = me.validatorRefMap[type][this.validator.name].compareTo + tvId;
                let max = Ext.getCmp(maxFld),
                    maxVal = Number(max.getValue())
                    ;
                if(maxVal > 0){
                    if (Number(v) > maxVal) {
                        return me.validatorRefMap[type][this.validator.name].errMsg;
                    }
                    max.clearInvalid();
                }
                return true;
            },
            maxGtMin: function(v) {
                const minFld = me.validatorRefMap[type][this.validator.name].compareTo + tvId;
                let min = Ext.getCmp(minFld),
                    minVal = Number(min.getValue())
                    ;
                if(minVal > 0){
                    if (v && Number(v) < minVal) {
                        return me.validatorRefMap[type][this.validator.name].errMsg;
                    }
                    min.clearInvalid();
                }
                return true;
            }
        };
        return ov;
    }
    /*
        NOTE: dirtyOnChange() is a replacement for how the change event is currently applied. However,
        I expect the use of this method to be unnecessary as Ext JS's tracking of fields' dirty
        states seems better-suited to evaluating whether there are really new form values to be saved. I suspect
        that the primary reason MODx set up it's own dirty marking routines was to overcome issues caused by
        RTEs dynamically inserting dummy content initially to empty RTE fields to allow them to be clickable (TinyMCE
        definitely does this by design when its RTE is assigned to a textarea [as opposed to a non-form element such as a div]).
        This causes forms under these conditions to ALWAYS be dirty.

        The proposed change that overcomes this particluar issue is handled with the new onBeforeUnload() method and
        its associated listener in the MODx.FormPanel class (see modx.panel.js).
    */
    ,tvOptsListeners: function(tvId, s) {
        const me = this;
        let ol = {
            dirtyOnChange: {
                "change": {
                    fn: function(){
                        // this.markPanelDirty();
                    },
                    scope: s
                }
            }
        };
        return ol;
    }

    /*
        Declare the standard built-in tv types so we can automate transformations and visibility
        properties for the core types and allow custom tv types to specify their own requirements
        (such as whether or not to show Input Option Values and the Default Value fields)
    */
    ,tvInputTypes: [
        'autotag',
        'checkbox',
        'date',
        'email',
        'file',
        'hidden',
        'image',
        'list-multiple-legacy',
        'listbox-multiple',
        'listbox',
        'number',
        'option', // radio option, would like deprecate and change key to radio
        'radio',
        'resourcelist',
        'richtext',
        'tag',
        'text',
        'textarea',
        'url'
    ]

});
Ext.reg('modx-panel-tv',MODx.panel.TV);



MODx.panel.TVInputProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-tv-input-properties'
        ,title: _('tv_input_options')
        ,header: false
		,border: false
        ,defaults: {
            border: false
            ,defaults: {
                labelSeparator: ''
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
        }
        ,cls: 'form-with-labels'
        ,items: [{
            html: _('tv_input_options_msg')
            ,itemId: 'desc-tv-input-properties'
            ,xtype: 'modx-description'
        },{
            layout: 'form'
			,border: false
			,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,labelSeparator: ''
			,items: [{
				xtype: 'modx-combo-tv-input-type'
				,fieldLabel: _('tv_type')
				,description: MODx.expandHelp ? '' : _('tv_type_desc')
				,name: 'type'
				,id: 'modx-tv-type'
				,itemid: 'fld-type'
				,anchor: '100%'
				,value: config.record.type || 'text'
				,listeners: {
					'select': {fn:this.showInputProperties,scope:this}
				}
			},{
                xtype: 'label'
                ,forId: 'modx-tv-type'
                ,html: _('tv_type_desc')
                ,cls: 'desc-under'
            },{
				xtype: 'textarea'
				,fieldLabel: _('tv_elements')
				,description: MODx.expandHelp ? '' : _('tv_elements_desc')
				,name: 'els'
				,id: 'modx-tv-elements'
				,itemId: 'fld-els'
				,anchor: '100%'
				,grow: true
				,maxHeight: 160
				,value: config.record.elements || ''
			},{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'modx-tv-elements'
                ,html: _('tv_elements_desc')
                ,cls: 'desc-under'
            },{
                // Reducing this item's initial config to bare bones minimum, as it will be dynamically replaced below
                xtype: 'textarea'
				,id: 'modx-tv-default-text'
				,itemId: 'fld-default_text'
			},{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'modx-tv-default-text'
                ,html: _('tv_default_desc')
                ,cls: 'desc-under'
            },{
                xtype: 'fieldset',
                itemId: 'input-options-fs',
                autoHeight: true,
                style: 'border:0',
                bodyStyle: 'padding:0',
                labelSeparator: '',
                defaults: {
                    xtype: 'textfield'
                    ,msgTarget: 'under'
                },
                items: []
    		},{
				id: 'legacy-input-options'
				,autoHeight: true
			}]
        }]
    });
    MODx.panel.TVInputProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TVInputProperties,MODx.Panel,{
    markPanelDirty: function() {
        // Ext.getCmp('modx-panel-tv').markDirty();
    }

    ,getDefaultValueItem: function(type, value) {

        value = typeof value !== 'undefined' ? value : '' ;
        // Set default type to Text TV
        type = typeof type === 'undefined' ? 'text' : type ;

        // Establish the default value item properties common to all tv types
        const defaultProps = {
                xtype: 'textfield'
                ,fieldLabel: _('tv_default')
                ,description: MODx.expandHelp ? '' : _('tv_default_desc')
                ,name: 'default_text'
                ,id: 'modx-tv-default-text'
                ,itemId: 'fld-default_text'
                ,anchor: '100%'
                ,value: value
            };
        let typeProps = {},
            item
            ;

        switch (type) {

            case 'checkbox':
                typeProps = {
                    fieldLabel: _('tv_default_option')
                };
                break;

            case 'email':
                typeProps = {
                    fieldLabel: _('tv_default_email')
                    ,vtype: 'email'
                };
                break;

            case 'date':
                typeProps = {
                    xtype: 'xdatetime'
                    ,fieldLabel: _('tv_default_datetime')
                    ,allowBlank: true
                    ,anchor: '50%'
                };
                break;


            case 'file':
                typeProps = {
                    xtype: 'modx-combo-browser'
                    ,browserEl: 'modx-browser'
                    ,fieldLabel: _('tv_default_file')
                    ,openTo: this.record.openTo || ''
                    ,triggerClass: 'x-form-code-trigger'
                    ,maxLength: 255
                    ,hideMode: 'offsets'
                    ,anchor: '50%'
                };
                break;

            // TBD: Would be nice to provide preview
            case 'image':
                typeProps = {
                    xtype: 'modx-combo-browser'
                    ,browserEl: 'modx-browser'
                    ,fieldLabel: _('tv_default_image')
                    ,openTo: this.record.openTo || ''
                    ,triggerClass: 'x-form-code-trigger'
                    ,maxLength: 255
                    ,hideMode: 'offsets'
                    ,anchor: '50%'
                };
                break;

            case 'listbox':
                typeProps = {
                    fieldLabel: _('tv_default_option')
                };
                break;

            case 'listbox-multiple':
            case 'listbox-multiple-legacy':
                typeProps = {
                    fieldLabel: _('tv_default_options')
                };
                break;

            case 'number':
                typeProps = {
                    xtype: 'numberfield'
                    ,width: 200
                    ,anchor: ''
                };
                break;

            case 'option':
            case 'radio':
                typeProps = {
                    fieldLabel: _('tv_default_option')
                };
                break;

            case 'resourcelist':
                typeProps = {
                    xtype: 'numberfield'
                    ,fieldLabel: _('tv_default_resource')
                };
                break;

            case 'richtext':
                typeProps = {
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default_text')
                    ,grow: true
                    ,maxHeight: 250
                };
                break;

            case 'tag':
                typeProps = {
                    fieldLabel: _('tv_default_tag')
                };
                break;

            case 'text':
                typeProps = {
                    fieldLabel: _('tv_default_text')
                };
                break;

            case 'textarea':
                typeProps = {
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default_text')
                    ,grow: true
                    ,maxHeight: 250
                };
                break;

            case 'url':
                typeProps = {
                    vtype: 'url'
                    ,fieldLabel: _('tv_default_url')
                };
                break;

            default:
        }
        item = Object.assign({}, defaultProps, typeProps);
        return item;
    }

    ,showInputProperties: function(cb,rc,i) {

        /*
            NOTE: The ext property 'startValue' gets applied to a component only after its has been changed, and
            is thus the most direct way of assessing whether the tvtype has been chosen and/or changed
        */
        const   formCmp = this.getComponent(1),
                tvPanel = Ext.getCmp('modx-panel-tv'),
                typeChanged = cb.hasOwnProperty('startValue') ? true : false ,
                type = cb.getValue(),
                tvId = this.config.record.id || '',
                optListeners = tvPanel.tvOptsListeners(tvId, this),
                optValidators = tvPanel.tvOptsValidators(tvId, type),
                optsFieldset = formCmp.getComponent('input-options-fs'),
                legacyOpts = Ext.get('legacy-input-options'),
                useLegacyLoader = tvPanel.tvInputTypes.indexOf(type) === -1 ? true : false,
                inputOptValsItem = Ext.getCmp('modx-tv-elements'),
                inputDefaultValItem = Ext.getCmp('modx-tv-default-text'),
                inputDefaultValItemVal = typeChanged ? inputDefaultValItem.getValue() : this.config.record.default_text,
                hideInputOptValsItemFor = ['autotag','date','email','file','hidden','image','number','resourcelist','richtext','text','textarea','url','migx','migxdb'],
                hideInputDefaultValItemFor = ['autotag','migx','migxdb']
            ;

        if(inputOptValsItem){
            if(hideInputOptValsItemFor.indexOf(type) !== -1){
                inputOptValsItem.hide().nextSibling().hide();
            } else {
                inputOptValsItem.show().nextSibling().show();
            }
        }
        if(inputDefaultValItem){
            if(hideInputDefaultValItemFor.indexOf(type) !== -1){
                inputDefaultValItem.hide().nextSibling().hide();
            } else {
                inputDefaultValItem.clearInvalid();
                /*
                    For cases where the default value field (and its help text) was previously hidden,
                    show the help component here before destroying the item it references
                */
                inputDefaultValItem.nextSibling().show();

                const   container = inputDefaultValItem.ownerCt,
                        key = container.items.keys.indexOf('fld-default_text')
                        ;

                let newDefaultInput = this.getDefaultValueItem(type, inputDefaultValItemVal);

                inputDefaultValItem.destroy();
                container.insert(key, newDefaultInput);
                formCmp.doLayout();
            }
        }

        if (useLegacyLoader) {

            let pu = legacyOpts.getUpdater();
            pu.loadScripts = true;
            optsFieldset.removeAll();
            try {
                pu.update({
                    url: MODx.config.connector_url
                    ,method: 'GET'
                    ,params: {
                       action: 'element/tv/renders/getInputProperties'
                       ,context: 'mgr'
                       ,tv: this.config.record.id
                       ,type: type || 'default'
                    }
                    ,scripts: true
                    // ,discardUrl: true
                });
            } catch(e) {
                MODx.debug(e);
            }

        } else {

            legacyOpts.update('');
            try {
                MODx.Ajax.request({
                    url: MODx.config.connector_url
                    ,params: {
                       action: 'element/tv/configs/getInputConfigs'
                       ,context: 'mgr'
                       ,tv: this.config.record.id
                       ,type: type || 'default'
                       ,expandHelp: MODx.expandHelp
                   }
                   ,listeners: {
                        "success": {
                            fn: function(r) {

                                if (inputOptValsItem && r.hasOwnProperty('hideInputOptValsItem') && (r.hideInputOptValsItem == "true" || r.hideInputOptValsItem == 1)) {
                                    inputOptValsItem.hide().nextSibling().hide();
                                }
                                if (inputDefaultValItem && r.hasOwnProperty('hideInputDefaultValItem') && (r.hideInputDefaultValItem == "true" || r.hideInputDefaultValItem == 1)) {
                                    inputDefaultValItem.hide().nextSibling().hide();
                                }

                                // Make adjustments to returned config
                                if (r.hasOwnProperty("optsItems") && r.optsItems.length > 0) {
                                    Ext.each(r.optsItems, function(obj, i){

                                        // Replace named listener(s) with its/their associated class method(s)
                                        if (this.hasOwnProperty("listeners")) {
                                            let fsl = this.listeners.replace(/\s/g, "");
                                            if(fsl.indexOf(",") > 0){
                                                fsl = fsl.split(",");
                                                let listenerList = [];
                                                fsl.forEach(function(itm, i){
                                                    if(optListeners.hasOwnProperty(itm)){
                                                        listenerList.push(optListeners[itm]);
                                                    }
                                                });
                                                this.listeners = Object.assign({}, ...listenerList);
                                            } else {
                                                this.listeners = optListeners.hasOwnProperty(fsl) ? optListeners[fsl] : null ;
                                            }
                                        }

                                        // Replace named validator with its associated class method
                                        if(this.hasOwnProperty("validator")){
                                            let fsv = this.validator.trim();
                                            this.validator = optValidators.hasOwnProperty(fsv) ? optValidators[fsv] : null ;
                                        }

                                        // Transform all regex type property values from string to usable expression
                                        if(this.hasOwnProperty("regex")){
                                            let rx = this.regex.trim();
                                            this.regex = new RegExp(rx);
                                        }
                                        if(this.hasOwnProperty("maskRe")){
                                            let mr = this.maskRe.trim();
                                            this.maskRe = new RegExp(mr);
                                        }
                                    });

                                    if(typeChanged){
                                        optsFieldset.removeAll();
                                    }
                                    optsFieldset.add(r.optsItems);

                                // No option items exist for certain fields (file, hidden, etc.),
                                } else {
                                    if(typeChanged){
                                        optsFieldset.removeAll();
                                    }
                                }
                                if(typeChanged){
                                    // this.markPanelDirty();
                                    formCmp.doLayout();
                                }
                            }
                            ,scope: this
                        }
                        ,"failure": {
                            fn: function(r) {
                                // TBD
                            }
                            ,scope: this
                        }
                    }
                });
            } catch(e) {
                MODx.debug(e);
            }
        }
    }
});
Ext.reg('modx-panel-tv-input-properties',MODx.panel.TVInputProperties);


MODx.panel.TVOutputProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-tv-output-properties'
        ,title: _('tv_output_options')
        ,header: false
        ,layout: 'form'
        ,cls: 'form-with-labels'
        ,defaults: {
            border: false
            ,defaults: {
                labelSeparator: ''
                ,msgTarget: 'under'
                ,validationEvent: 'change'
                ,validateOnBlur: false
            }
        }
        ,items: [{
            html: _('tv_output_options_msg')
            ,itemId: 'desc-tv-output-properties'
            ,xtype: 'modx-description'
        },{
            layout: 'form'
			,border: false
			,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,items: [{
                xtype: 'modx-combo-tv-widget'
                ,fieldLabel: _('tv_output_type')
                ,name: 'display'
                ,hiddenName: 'display'
                ,id: 'modx-tv-display'
                ,itemId: 'fld-display'
                ,value: config.record.display || 'default'
                ,anchor: '100%'
                ,listeners: {
                    'select': {fn:this.showOutputProperties,scope:this}
                }
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,labelSeparator: ''
                ,forId: 'modx-tv-display'
                ,html: _('tv_output_type_desc')
                ,cls: 'desc-under'
            },{
				id: 'modx-widget-props'
				,autoHeight: true
			}]
		}]
    });
    MODx.panel.TVOutputProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TVOutputProperties,MODx.Panel,{
    showOutputProperties: function(cb,rc,i) {
        Ext.getCmp('modx-panel-tv').markDirty();
        var pu = Ext.get('modx-widget-props').getUpdater();
        pu.loadScripts = true;
        try {
            pu.update({
                url: MODx.config.connector_url
                ,method: 'GET'
                ,params: {
                   'action': 'element/tv/renders/getProperties'
                   ,'context': 'mgr'
                   ,'tv': this.config.record.id
                   ,'type': cb.getValue() || 'default'
                }
                ,scripts: true
            });
        } catch(e) {MODx.debug(e);}
    }
});
Ext.reg('modx-panel-tv-output-properties',MODx.panel.TVOutputProperties);


MODx.grid.ElementSources = function(config) {
    var src = new MODx.combo.MediaSource();
    src.getStore().load();

    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-element-sources'
        ,fields: ['context_key','source','name']
        ,autoHeight: true
        ,primaryKey: 'id'
        ,columns: [{
            header: _('context')
            ,dataIndex: 'context_key'
        },{
            header: _('source')
            ,dataIndex: 'source'
            ,xtype: 'combocolumn'
            ,editor: src
            ,gridId: 'modx-grid-element-sources'
        }]
    });
    MODx.grid.ElementSources.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(['context_key','source']);
};
Ext.extend(MODx.grid.ElementSources,MODx.grid.LocalGrid,{
    getMenu: function() {
        return [];
    }
});
Ext.reg('modx-grid-element-sources',MODx.grid.ElementSources);
