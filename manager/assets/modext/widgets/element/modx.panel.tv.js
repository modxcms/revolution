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
            action: 'Element/TemplateVar/Get'
        }
        ,id: 'modx-panel-tv'
        ,cls: 'container form-with-labels'
        ,class_key: 'modTemplateVar'
        ,tv: ''
        ,bodyStyle: ''
        ,autoWidth: true
        ,monitorResize: true
        ,previousFileSource: config.record.source != null ? config.record.source : MODx.config.default_media_source
        ,items: [{
            id: 'modx-tv-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('general_information')
            ,layout: 'form'
            ,id: 'modx-tv-form'
            ,itemId: 'form-tv'
            ,labelWidth: 150
            ,forceLayout: true
            ,defaults: {
                border: false
                ,msgTarget: 'side'
                ,layout: 'form'
            }
            ,items: [{
                html: '<p>'+_('tv_tab_general_desc')+'</p>'
                ,id: 'modx-tv-msg'
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
                                ,id: 'modx-tv-id'
                                ,value: config.record.id || MODx.request.id
                            },{
                                xtype: 'hidden'
                                ,name: 'props'
                                ,id: 'modx-tv-props'
                                ,value: config.record.props || null
                            },{
                                xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('tv_name_desc', {
                                    tag: `<span class="copy-this">[[*<span class="example-replace-name">${_('example_tag_tv_name')}</span>]]</span>`
                                })
                                ,name: 'name'
                                ,id: 'modx-tv-name'
                                ,maxLength: 50
                                ,enableKeyEvents: true
                                ,allowBlank: false
                                ,value: config.record.name
                                ,tabIndex: 1
                                ,listeners: {
                                    keyup: {
                                        fn: function(cmp, e) {
                                            const   title = this.formatMainPanelTitle('tv', this.config.record, cmp.getValue(), true),
                                                    tagTitle = title && title.length > 0 ? title : _('example_tag_tv_name')
                                                ;
                                            cmp.nextSibling().getEl().child('.example-replace-name').update(tagTitle);
                                            MODx.setStaticElementPath('tv');
                                        }
                                        ,scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-name'
                                ,html: _('tv_name_desc', {
                                    tag: `<span class="copy-this">[[*<span class="example-replace-name">${_('example_tag_tv_name')}</span>]]</span>`
                                })
                                ,cls: 'desc-under'
                                ,listeners: {
                                    afterrender: {
                                        fn: function(cmp) {
                                            this.insertTagCopyUtility(cmp, 'tv');
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
                                ,description: MODx.expandHelp ? '' : _('tv_category_desc')
                                ,name: 'category'
                                ,id: 'modx-tv-category'
                                ,value: config.record.category || 0
                                ,tabIndex: 2
                                ,listeners: {
                                    afterrender: {scope:this,fn:function(f,e) {
                                            MODx.setStaticElementPath('tv');
                                    }}
                                    ,select: {scope:this,fn:function(f,e) {
                                        MODx.setStaticElementPath('tv');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-category'
                                ,html: _('tv_category_desc')
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
                                xtype: 'textfield'
                                ,fieldLabel: _('tv_caption')
                                ,description: MODx.expandHelp ? '' : _('tv_caption_desc')
                                ,name: 'caption'
                                ,id: 'modx-tv-caption'
                                ,tabIndex: 3
                                ,value: config.record.caption
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-caption'
                                ,html: _('tv_caption_desc')
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
                            ,items: [{
                                xtype: 'numberfield'
                                ,fieldLabel: _('tv_rank')
                                ,description: MODx.expandHelp ? '' : _('tv_rank_desc')
                                ,name: 'rank'
                                ,id: 'modx-tv-rank'
                                ,maxLength: 4
                                ,allowNegative: false
                                ,allowBlank: false
                                ,tabIndex: 4
                                ,value: config.record.rank || 0
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-rank'
                                ,html: _('tv_rank_desc')
                                ,cls: 'desc-under'
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
                                ,description: MODx.expandHelp ? '' : _('tv_description_desc')
                                ,name: 'description'
                                ,id: 'modx-tv-description'
                                ,maxLength: 255
                                ,tabIndex: 5
                                ,value: config.record.description || ''
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-description'
                                ,html: _('tv_description_desc')
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
                                ,boxLabel: _('tv_lock')
                                ,description: MODx.expandHelp ? '' : _('tv_lock_desc')
                                ,name: 'locked'
                                ,id: 'modx-tv-locked'
                                ,inputValue: 1
                                ,tabIndex: 6
                                ,checked: config.record.locked || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-locked'
                                ,html: _('tv_lock_desc')
                                ,cls: 'desc-under toggle-slider-above'
                            },{
                                xtype: 'xcheckbox'
                                ,hideLabel: true
                                ,boxLabel: _('clear_cache_on_save')
                                ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_desc')
                                ,name: 'clearCache'
                                ,id: 'modx-tv-clear-cache'
                                ,inputValue: 1
                                ,tabIndex: 7
                                ,checked: Ext.isDefined(config.record.clearCache) || true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-clear-cache'
                                ,html: _('clear_cache_on_save_desc')
                                ,cls: 'desc-under toggle-slider-above'
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
                                ,description: MODx.expandHelp ? '' : _('is_static_tv_desc')
                                ,name: 'static'
                                ,id: 'modx-tv-static'
                                ,inputValue: 1
                                ,checked: config.record['static'] || false
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-static'
                                ,id: 'modx-tv-static-help'
                                ,html: _('is_static_tv_desc')
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
                    ,id: 'tv-static-options-fs'
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
                                ,id: 'modx-tv-static-source'
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
                                            this.onChangeStaticSource(cmp, 'tv');
                                        },
                                        scope: this
                                    }
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-static-source'
                                ,id: 'modx-tv-static-source-help'
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
                                this.getStaticFileField('tv', config.record),{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-tv-static-file'
                                ,id: 'modx-tv-static-file-help'
                                ,html: _('static_file_desc')
                                ,cls: 'desc-under'
                            }]
                        }]
                    }]
                    ,listeners: {
                        afterrender: function(cmp) {
                            const isStaticCmp = Ext.getCmp('modx-tv-static');
                            if (isStaticCmp) {
                                this.isStatic = isStaticCmp.checked;
                                const   switchField = 'modx-tv-static',
                                        toggleFields = ['modx-tv-static-file','modx-tv-static-source']
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
            }]
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
                html: '<p>'+_('tv_tab_tmpl_access_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-tv-template'
                ,itemId: 'grid-template'
                ,cls:'main-wrapper'
                ,tv: config.tv
                ,preventRender: true
                ,anchor: '100%'
                ,listeners: {
                    rowclick: {fn:this.markDirty,scope:this}
                    ,afteredit: {fn:this.markDirty,scope:this}
                    ,afterRemoveRow: {fn:this.markDirty,scope:this}
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
                html: '<p>'+_('tv_tab_sources_desc')+'</p>'
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
                    rowclick: {fn:this.markDirty,scope:this}
                    ,afteredit: {fn:this.markDirty,scope:this}
                    ,afterRemoveRow: {fn:this.markDirty,scope:this}
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
                html: '<p>'+_('tv_tab_access_desc')+'</p>'
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
                    rowclick: {fn:this.markDirty,scope:this}
                    ,afteredit: {fn:this.markDirty,scope:this}
                    ,afterRemoveRow: {fn:this.markDirty,scope:this}
                }
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,itemId: 'panel-properties'
            ,elementPanel: 'modx-panel-tv'
            ,elementId: config.tv
            ,elementType: 'MODX\\Revolution\\modTemplateVar'
            ,record: config.record
        }],{
            id: 'modx-tv-editor-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-tv-tabpanel-'+config.tv
            ,stateEvents: ['tabchange']
            ,hideMode: 'offsets'
            ,anchor: '100%'
            ,getState: function() {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            }
        })]
        ,useLoadingMask: true
        ,listeners: {
            setup: {fn:this.setup,scope:this}
            ,success: {fn:this.success,scope:this}
            ,failure: {fn:this.failure,scope:this}
            ,beforeSubmit: {fn:this.beforeSubmit,scope:this}
            ,failureSubmit: {
                fn: function() {
                    this.showErroredTab(this.errorHandlingTabs, 'modx-tv-editor-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{

    initialized: false

    /**
     * @property {String} currentTvType - Used to store tv type (xtype) for easy reference by other methods
     */
    ,currentTvType: null

    /**
     * @property {Number} tvId - Used to store this record's id for easy reference by other methods
     */
    ,tvId: null

    /**
     * @property {Object} nativeTypes - Defines the standard, built-in tv types so we can automate transformations
     * and visibility properties for those standard types and allow custom tv types to specify their own requirements
     * (such as whether or not to show Input Option Values and the Default Value fields)
     */
    ,nativeTypes: {
        input: [
            'autotag',
            'checkbox',
            'date',
            'email',
            'file',
            'hidden',
            'image',
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
        ],
        output: [
            'date',
            'default',
            'delim',
            'htmltag',
            'image',
            'richtext',
            'string',
            'text',
            'url'
        ]
    }

    /**
     * @property {Object} newLoaderTypes - Extras that want to use the new input and output options loader
     * should override this property using the setNewLoaderType method in their component's js file, adding to the
     * values that may already exist, following the same data scheme used above in the nativeTypes property.
     * Using apply/applyIf is not advisable here, as it's the inner properties' array values that need to be appended.
     */
    ,newLoaderTypes: {
        input: [],
        output: []
    }

    /**
     * @property {Function} addNewLoaderType - Inserts given tv type, and optionally output render type, into newLoaderTypes object
     *
     * @param {String|Array} renderTypeKeys - The TV type (e.g., 'date') or output render type (e.g., 'string') or both in an array if differently named (e.g., ['tvName', 'renderName'])
     * @param {String|Array} propTypes - Optional; Which property panel(s) use the given renderTypeKey (currently either 'input' or 'output')
     *
     * Example usage:
     * addNewLoaderType('tvName', 'input'); - adds 'tvName' to newLoaderTypes.input array
     * addNewLoaderType('tvName'); - adds 'tvName' to newLoaderTypes.input array and an output render by the same name ('tvName') to the newLoaderTypes.output array
     * addNewLoaderType(['tvName', 'renderName'], ['input', 'output']); OR simply addNewLoaderType(['tvName', 'renderName']); - adds tvName' to newLoaderTypes.input array and 'renderName' to newLoaderTypes.output array
     *
     */
    ,addNewLoaderType: function(renderTypeKeys, propTypes) {

        propTypes = propTypes || ['input', 'output'];

        if (typeof propTypes === 'string') {
            // String indicates a single propType was given, meaning only one renderTypeKey should have been given
            if (typeof renderTypeKeys !== 'string') {
                console.error('addNewLoaderType: The renderTypeKeys parameter must be a string when the propTypes parameter is a string. The value passed for renderTypeKeys was: ', renderTypeKeys);
                return false;
            }
            this.newLoaderTypes[propTypes].push(renderTypeKeys);
        } else {
            // Let's just make sure this is an array and it's not empty
            if(Ext.isArray(propTypes) && propTypes.length > 0) {
                const matchKeystoTypes = Ext.isArray(renderTypeKeys) && renderTypeKeys.length == propTypes.length ? true : false ;
                Ext.each(propTypes, function(type, i) {
                    if (!this.newLoaderTypes.hasOwnProperty(type)) {
                        this.newLoaderTypes[type] = [];
                    }
                    if (matchKeystoTypes) {
                        this.newLoaderTypes[type].push(renderTypeKeys[i]);
                    } else {
                        this.newLoaderTypes[type].push(renderTypeKeys);
                    }
                }, this);
            } else {
                console.error('addNewLoaderType: There was a problem matching the renderTypeKeys to the propTypes');
                console.error('addNewLoaderType: renderTypeKeys - ', renderTypeKeys);
                console.error('addNewLoaderType: propTypes, - ', propTypes);
            }
        }
    }

    /**
     * @property {Function} useLegacyLoader - Provides backward compatibility for non-native (community-authored)
     * TVs that have not updated to the new configuration loader
     *
     * @param {String} tvType - The TV type (e.g., 'date')
     * @param {String} propType - The properties panel being loaded (currently either 'input' or 'output')
     * @returns {Boolean}
     */
    ,useLegacyLoader: function(tvType, propType) {
        let allNewLoaderTypes;
        if (this.newLoaderTypes.hasOwnProperty(propType) && this.newLoaderTypes[propType].length > 0) {
            allNewLoaderTypes = [...this.nativeTypes[propType], ...this.newLoaderTypes[propType]]
        } else {
            allNewLoaderTypes = this.nativeTypes[propType];
        }
        return allNewLoaderTypes.indexOf(tvType) === -1 ? true : false ;
    }

    /**
     * @property {Object} validatorRefMap - Provides an easy way to map differently named fields to a
     * common validator method, by tv type, without the need to pass in variables to the target method
     * from the field item's validator config
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

    /**
     * @property {Object} sharedComponentOverrides - Entry point for user-contributed TV
     * overrides of the global input properties (currently input options [elements] and
     * default value [default_text]).
     */
    ,sharedComponentOverrides: {}

    ,setup: function() {

        if (this.initialized) {
            this.clearDirty();
            return true;
        }
        /*
            The itemId (not id) of each form tab to be included/excluded; these correspond to the
            keys in each tab component's items property
        */
        this.errorHandlingTabs = ['form-tv'];
        this.errorHandlingIgnoreTabs = ['panel-properties','form-template','form-access','form-sources'];

        this.getForm().setValues(this.config.record);
        this.formatMainPanelTitle('tv', this.config.record);
        this.getElementProperties(this.config.record.properties);

        if (!Ext.isEmpty(this.config.record.sources)) {
            Ext.getCmp('modx-grid-element-sources').getStore().loadData(this.config.record.sources);
        }

        Ext.getCmp('modx-panel-tv-output-properties').showOutputProperties(Ext.getCmp('modx-tv-display'));
        Ext.getCmp('modx-panel-tv-input-properties').showInputProperties(Ext.getCmp('modx-tv-type'));

        this.fireEvent('ready',this.config.record);

        if (MODx.onLoadEditor) {
            MODx.onLoadEditor(this);
        }

        this.clearDirty();
        this.initialized = true;
        MODx.fireEvent('ready');
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

    ,validatorCustomDefs: {}

    /**
     * @property {Function} getValidatorDefs - Establishes special built-in validators and provides
     * an easy way for custom TVs to add their own via a custom override object
     *
     * @param {Number} tvId - The numeric id of the template variable
     * @param {String} type - The TV type (e.g., 'date')
     * @returns {Object} - The currently-available set of validator methods
     */
    ,getValidatorDefs: function(tvId, type) {
        // Here, 'this' refers to the current class (MODx.panel.TV)
        const tvp = this;
        // Within each validator, 'this' refers to the input element triggering the given method
        let validatorDefs = Ext.applyIf({
            minLtMax: function (v) {
                const maxFld = tvp.validatorRefMap[type][this.validator.name].compareTo + tvId;
                let max = Ext.getCmp(maxFld),
                    maxVal = Number(max.getValue())
                    ;
                if(maxVal > 0){
                    if (Number(v) > maxVal) {
                        return tvp.validatorRefMap[type][this.validator.name].errMsg;
                    }
                    max.clearInvalid();
                }
                return true;
            },
            maxGtMin: function(v) {
                const minFld = tvp.validatorRefMap[type][this.validator.name].compareTo + tvId;
                let min = Ext.getCmp(minFld),
                    minVal = Number(min.getValue())
                    ;
                if(minVal > 0){
                    if (v && Number(v) < minVal) {
                        return tvp.validatorRefMap[type][this.validator.name].errMsg;
                    }
                    min.clearInvalid();
                }
                return true;
            },
        }, this.validatorCustomDefs);

        return validatorDefs;
    }

    ,listenerCustomDefs: {}

    /**
     * @property {Function} getListenerDefs - Establishes special built-in listeners and provides
     * an easy way for custom TVs to add their own via a custom override object
     *
     * @param {Number} tvId - The numeric id of the template variable
     * @param {Object} propsPanel - A reference to the MODx.panel.TVInputProperties instance
     * @returns {Object} - The currently-available set of listener methods
     */
    ,getListenerDefs: function(tvId, propsPanel) {

        const tvp = this;

        let listenerDefs = Ext.applyIf({
            insertHelpExampleOnClick: {
                render: {
                    fn: function(el) {
                        this.insertHelpExample(el);
                    },
                    scope: tvp
                }
            }
        }, this.listenerCustomDefs);

        return listenerDefs;
    }

    /**
     * @property {Function} insertHelpExample - Places highlighted code sample from field description
     * into its associated field when clicked on
     *
     * @param {Object} cmp - The Ext.Component object containing the description field
     */
    ,insertHelpExample: function(cmp) {
        return cmp.getEl().on({
            click: function(el) {
                const targetEl = arguments[1];
                if(targetEl.classList.contains('example-input')) {
                    const   exampleTxt = targetEl.textContent,
                            inputEl = cmp.previousSibling()
                        ;
                    inputEl.setValue(exampleTxt);
                }
            },
            scope: cmp
        });
    }

    /**
     * @property {Function} updateFieldConfigs - Recursively examine properties in items object
     * and replace named listeners and validator with their full methods, as well as other
     * transformations to ensure full functionality. Used by input and output properties panels.
     *
     * @param {Object} items - The array of Ext component fields to work on
     * @param {Object} listenerDefs - The currently-available set of listeners available
     * @param {Object} validatorDefs - The currently-available set of validators available
     *
     */
    ,updateFieldConfigs: function(items, listenerDefs, validatorDefs) {
        const me = this;
        Ext.each(items, function(obj, i){
            if (obj.hasOwnProperty('items') && obj.items.length > 0) {
                me.updateFieldConfigs(obj.items, listenerDefs, validatorDefs);
            } else {
                // Replace named listener(s) with its/their associated class method(s)
                if (this.hasOwnProperty("listeners")) {
                    let listenersCfg = this.listeners.replace(/\s/g, "");
                    if(listenersCfg.indexOf(",") > 0){
                        listenersCfg = listenersCfg.split(",");
                        let listenerList = [];
                        listenersCfg.forEach(function(itm, i){
                            if(listenerDefs.hasOwnProperty(itm)){
                                listenerList.push(listenerDefs[itm]);
                            }
                        });
                        this.listeners = Object.assign({}, ...listenerList);
                    } else {
                        this.listeners = listenerDefs.hasOwnProperty(listenersCfg) ? listenerDefs[listenersCfg] : null ;
                    }
                } else {
                    // Add default listener for help items
                    if (this.xtype && this.xtype === 'label') {
                        this.listeners = listenerDefs.insertHelpExampleOnClick;
                    }
                }

                // Replace named validator with its associated class method
                if(this.hasOwnProperty("validator")){
                    let fsv = this.validator.trim();
                    this.validator = validatorDefs.hasOwnProperty(fsv) ? validatorDefs[fsv] : null ;
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
            }
        });
    }

});
Ext.reg('modx-panel-tv',MODx.panel.TV);

/**
 * @class MODx.panel.TVInputProperties
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-tv-input-properties
 */
MODx.panel.TVInputProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-tv-input-properties'
        ,title: _('tv_tab_input_options')
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
            html: _('tv_tab_input_options_desc')
            ,itemId: 'desc-tv-input-properties'
            ,xtype: 'modx-description'
        },{
            layout: 'form'
            ,border: false
            ,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,labelSeparator: ''
            ,defaults: {
                anchor: '100%'
                ,msgTarget: 'under'
            }
            ,items: [{
                xtype: 'modx-combo-tv-input-type'
                ,fieldLabel: _('tv_type')
                ,description: MODx.expandHelp ? '' : _('tv_type_desc')
                ,name: 'type'
                ,id: 'modx-tv-type'
                ,itemid: 'fld-type'
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
                /*
                    Reducing this and next 3 items' initial config to bare-bones minimum,
                    as they will be dynamically replaced via updateSharedComponent each time
                    the tv type is changed
                */
                xtype: 'textarea'
                ,id: 'modx-tv-elements'
                ,itemId: 'fld-elements'
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'modx-tv-elements'
            },{
                xtype: 'textarea'
                ,id: 'modx-tv-default-text'
                ,itemId: 'fld-default-text'
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: 'modx-tv-default-text'
            },{
                xtype: 'fieldset',
                id: 'tv-input-opts-fs',
                autoHeight: true,
                cls: 'form-row-wrapper',
                labelSeparator: '',
                defaults: {
                    layout: 'column',
                    labelSeparator: '',
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: []
    		},{
				id: 'modx-input-props',
                autoHeight: true
            }]
        }]
    });
    MODx.panel.TVInputProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TVInputProperties,MODx.Panel,{
    markPanelDirty: function() {
        Ext.getCmp('modx-panel-tv').markDirty();
    }

    ,isNativeType: true

    /**
     * @property {Function} updateSharedComponent - Allows manipulation of input property components common to all tv types
     * based on the current tv type. User overrides possible via sharedComponentOverrides property
     *
     * @param {String} type - The TV type (e.g., 'date')
     * @param {Object} fieldCmp - The field's Ext component
     * @param {mixed} value - The field's currently stored value
     * @param {Boolean} updateSibling - Whether to alter the sibling component (inline help); default is true
     */
    ,updateSharedComponent: function(type, fieldCmp, value, updateSibling) {

        value = typeof value !== 'undefined' ? value : '' ;
        updateSibling = typeof updateSibling === 'undefined' || updateSibling === true ? true : false ;

        const   tvPanel = Ext.getCmp('modx-panel-tv'),
                container = fieldCmp.ownerCt,
                fieldId = fieldCmp.id,
                fieldItemId = fieldCmp.itemId,
                itemKey = container.items.keys.indexOf(fieldItemId),
                siblingKey = itemKey + 1,
                siblingCmp = fieldCmp.nextSibling(),
                optsDbExample_1 = '@SELECT `pagetitle`,`id` FROM `[[+PREFIX]]site_content` WHERE `published`=1 AND `deleted`=0 AND `template`=1',
                optsDbExample_2 = '@SELECT "-none-" AS `pagetitle`, 0 AS `id` UNION ALL SELECT `pagetitle`,`id` FROM `[[+PREFIX]]site_content` WHERE `published`=1 AND `deleted`=0 AND `template`=1'
                ;
        let defaultProps = {},
            typeProps = {},
            defaultHelp = {},
            helpProps = {},
            item,
            helpLexKey,
            helpUseLexKey,
            helpText
            ;
        if (this.isNativeType) {
            switch(fieldId) {
                case 'modx-tv-elements':
                    helpLexKey = 'tv_elements_'+type+'_desc';
                    break;
                case 'modx-tv-default-text':
                    helpLexKey = 'tv_default_'+type+'_desc';
                    break;
            }
        } else {
            switch(fieldId) {
                case 'modx-tv-elements':
                    helpLexKey = 'tv_elements_desc';
                    break;
                case 'modx-tv-default-text':
                    helpLexKey = 'tv_default_desc';
                    break;
            }
        }
        helpUseLexKey = typeof _(helpLexKey) !== 'undefined' ? helpLexKey : helpUseLexKey ;
        helpText = _(helpUseLexKey);

        switch(fieldId) {
            case 'modx-tv-elements':
                defaultProps = {
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_elements')
                    ,description: MODx.expandHelp ? '' : _(helpUseLexKey, { example_1: optsDbExample_1, example_2: optsDbExample_2})
                    ,name: 'els'
                    ,id: 'modx-tv-elements'
                    ,itemId: 'fld-elements'
                    ,grow: true
                    ,maxHeight: 160
                    ,value: value
                    ,plugins: new AddFieldUtilities.plugin.Class
                };
                defaultHelp = {
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-tv-elements'
                    ,html: _(helpUseLexKey, { example_1: optsDbExample_1, example_2: optsDbExample_2})
                    ,cls: 'desc-under'
                    ,listeners: {
                        render: {
                            fn: function(el) {
                                this.insertHelpExample(el);
                            },
                            scope: tvPanel
                        }
                    }
                };
                if (this.isNativeType) {
                    defaultProps.allowBlank = false;
                    switch (type) {
                        case 'checkbox':
                            typeProps = {
                                fieldLabel: _('tv_elements_checkbox')
                            };
                            break;
                        case 'listbox':
                            typeProps = {
                                fieldLabel: _('tv_elements_listbox')
                            };
                            break;
                        case 'listbox-multiple':
                        case 'listbox-multiple-legacy':
                            typeProps = {
                                fieldLabel: _('tv_elements_listbox')
                            };
                            break;
                        case 'option':
                        case 'radio':
                            typeProps = {
                                fieldLabel: _('tv_elements_radio')
                            };
                            break;
                        case 'tag':
                            typeProps = {
                                fieldLabel: _('tv_elements_tag')
                            };
                            break;
                    }
                }
                break;

            case 'modx-tv-default-text':
                defaultProps = {
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_default')
                    ,description: MODx.expandHelp ? '' : _('tv_default_desc')
                    ,name: 'default_text'
                    ,id: 'modx-tv-default-text'
                    ,itemId: 'fld-default-text'
                    ,anchor: '100%'
                    ,value: value
                };
                defaultHelp = {
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-tv-default-text'
                    ,html: helpText
                    ,cls: 'desc-under'
                    ,listeners: {
                        render: {
                            fn: function(el) {
                                this.insertHelpExample(el);
                            },
                            scope: tvPanel
                        }
                    }
                };
                if (this.isNativeType) {
                    switch (type) {
                        case 'checkbox':
                            typeProps = {
                                fieldLabel: _('tv_default_options')
                            };
                            break;
                        case 'email':
                            typeProps = {
                                fieldLabel: _('tv_default_email')
                                ,vtype: 'email'
                            };
                            break;
                        case 'date':
                            const   now = new Date(),
                                    datetimeNow = Ext.util.Format.date(now, MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format)
                            ;
                            helpText = _(helpUseLexKey, {example_1: '+24', example_2: '-24', example_3: datetimeNow});
                            typeProps = {
                                xtype: 'combo'
                                ,fieldLabel: _('tv_default_date')
                                ,description: MODx.expandHelp ? '' : helpText
                                ,hiddenName: 'default_text'
                                ,store: new Ext.data.SimpleStore({
                                    fields: ['v','d']
                                    ,data: [
                                        ['',_('none')],
                                        ['now',_('default_date_now')],
                                        ['today',_('default_date_today')],
                                        ['yesterday',_('default_date_yesterday')],
                                        ['tomorrow',_('default_date_tomorrow')],
                                        ['custom',_('default_date_custom')]
                                    ]
                                })
                                ,displayField: 'd'
                                ,valueField: 'v'
                                ,mode: 'local'
                                ,editable: true
                                ,forceSelection: false
                                ,typeAhead: false
                                ,triggerAction: 'all'
                                ,anchor: '50%'
                                ,plugins: new AddFieldUtilities.plugin.Class
                            };
                            helpProps = {
                                html: helpText
                            };
                            break;
                        case 'file':
                            typeProps = {
                                xtype: 'modx-combo-browser'
                                ,browserEl: 'modx-browser'
                                ,fieldLabel: _('tv_default_file')
                                ,openTo: this.record.openTo || ''
                                ,source: this.record.source
                                ,allowedFileTypes: 'txt'
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
                                ,source: this.record.source
                                ,allowedFileTypes: MODx.config.upload_images
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
                                ,fieldLabel: _('tv_default_number')
                                ,anchor: '50%'
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
                                ,anchor: '50%'
                            };
                            break;
                        case 'textarea':
                            typeProps = {
                                xtype: 'textarea'
                                ,fieldLabel: _('tv_default_text')
                                ,grow: true
                                ,maxHeight: 250
                                ,anchor: '50%'
                            };
                            break;
                        case 'url':
                            typeProps = {
                                vtype: 'url'
                                ,fieldLabel: _('tv_default_url')
                            };
                            break;
                    }
                }
                break;
        }

        if (!this.isNativeType && tvPanel.sharedComponentOverrides.hasOwnProperty(type)) {
            const overrides = tvPanel.sharedComponentOverrides[type][fieldId];
            if (typeof overrides !== 'undefined') {
                const help = overrides.help;
                typeProps = overrides.config || {};
                if (typeProps.hasOwnProperty('hidden') && typeProps.hidden === true || help === false) {
                    helpProps.hidden = true;
                }
                if (!Ext.isEmpty(help)) {
                    typeProps.description = MODx.expandHelp ? '' : help ;
                    helpProps.html = help;
                }
                if (help === false) {
                    typeProps.description = '';
                }
            }
        }

        item = Ext.apply(defaultProps, typeProps);
        fieldCmp.clearInvalid();
        fieldCmp.destroy();
        container.insert(itemKey, item);

        if (updateSibling) {
            item = Ext.apply(defaultHelp, helpProps);
            siblingCmp.destroy();
            container.insert(siblingKey, item);
        }
    }

    ,showInputProperties: function(cb,rc,i) {

        /*
            NOTE: The ext property 'startValue' gets applied to a component only after its has been changed, and
            is thus the most direct way of assessing whether the tvtype has been chosen and/or changed. We can't
            simply test for isDirty, as it would return false when changing from a new type back to the original one.
        */
        const   formCmp = this.getComponent(1),
                tvPanel = Ext.getCmp('modx-panel-tv'),
                typeChanged = cb.hasOwnProperty('startValue') ? true : false ,
                type = cb.getValue(),
                tvId = this.config.record.id || '',
                listenerDefs = tvPanel.getListenerDefs(tvId, this),
                validatorDefs = tvPanel.getValidatorDefs(tvId, type),
                optsFieldset = Ext.getCmp('tv-input-opts-fs'),
                legacyOpts = Ext.get('modx-input-props'),
                useLegacyLoader = tvPanel.useLegacyLoader(type, 'input'),
                inputOptValsItem = Ext.getCmp('modx-tv-elements'),
                inputOptValsItemVal = typeChanged ? inputOptValsItem.getValue() : this.config.record.elements,
                inputDefaultValItem = Ext.getCmp('modx-tv-default-text'),
                inputDefaultValItemVal = typeChanged ? inputDefaultValItem.getValue() : this.config.record.default_text,
                hideInputOptValsItemFor = ['autotag','date','email','file','hidden','image','number','resourcelist','richtext','text','textarea','url'],
                hideInputDefaultValItemFor = ['autotag']
        ;

        tvPanel.currentTvType = type;
        tvPanel.tvId = tvId;
        this.isNativeType = tvPanel.nativeTypes['input'].indexOf(type) !== -1 ? true : false ;

        if(inputOptValsItem){
            if(hideInputOptValsItemFor.indexOf(type) !== -1){
                inputOptValsItem.allowBlank = true;
                inputOptValsItem.hide().nextSibling().hide();
            } else {
                this.updateSharedComponent(type, inputOptValsItem, inputOptValsItemVal);
                formCmp.doLayout();
                /*
                    Prevents this required field from immediately showing as invalid upon changing the tv type.
                    Because this component is dynamically generated, it needs to be fetched again here
                    with getCmp instead of using the inputOptValsItem variable
                */
                if (typeChanged) {
                    Ext.getCmp('modx-tv-elements').clearInvalid();
                    if(this.isNativeType){
                        inputOptValsItem.allowBlank = false;
                    }
                }
            }
        }
        if(inputDefaultValItem){
            if(hideInputDefaultValItemFor.indexOf(type) !== -1){
                inputDefaultValItem.hide().nextSibling().hide();
                // composite field labels have to be explicitly hidden
                if (inputDefaultValItem.initialConfig.xtype == 'xdatetime') {
                    inputDefaultValItem.getEl().up('.x-form-item').addClass('x-hide-display');
                }
            } else {
                this.updateSharedComponent(type, inputDefaultValItem, inputDefaultValItemVal);
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
                           action: 'Element/TemplateVar/Renders/GetInputProperties'
                           ,context: 'mgr'
                           ,tv: this.config.record.id
                           ,type: type || 'default'
                    }
                    ,scripts: true
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
                       action: 'Element/TemplateVar/Configs/GetInputPropertyConfigs'
                       ,context: 'mgr'
                       ,tv: this.config.record.id
                       ,type: type || 'default'
                       ,expandHelp: MODx.expandHelp
                   }
                   ,listeners: {
                        success: {
                            fn: function(r) {

                                // Make adjustments to returned config
                                if (r.hasOwnProperty('optsItems') && r.optsItems.length > 0) {
                                    tvPanel.updateFieldConfigs(r.optsItems, listenerDefs, validatorDefs);
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
                                    formCmp.doLayout();
                                }
                            }
                            ,scope: this
                        }
                        ,failure: {
                            fn: function(r) {
                                console.error(`showInputProperties failed to fetch the config for ${type} due to an error.`, r);
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

/**
 * @class MODx.panel.TVOutputProperties
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-tv-output-properties
 */
MODx.panel.TVOutputProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-tv-output-properties'
        ,title: _('tv_tab_output_options')
        ,header: false
        ,layout: 'form'
        ,cls: 'form-with-labels'
        ,defaults: {
            border: false
        }
        ,items: [{
            html: _('tv_tab_output_options_desc')
            ,itemId: 'desc-tv-output-properties'
            ,xtype: 'modx-description'
        },{
            layout: 'form'
            ,cls:'main-wrapper'
            ,labelAlign: 'top'
            ,labelSeparator: ''
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
                ,forId: 'modx-tv-display'
                ,html: _('tv_output_type_desc')
                ,cls: 'desc-under'
            },{
                xtype: 'fieldset',
                id: 'tv-output-opts-fs',
                autoHeight: true,
                cls: 'form-row-wrapper',
                defaults: {
                    layout: 'column',
                    labelSeparator: '',
                    anchor: '100%',
                    msgTarget: 'under'
                },
                items: []
    		},{
                id: 'modx-widget-props'
                ,autoHeight: true
            }]
        }]
    });
    MODx.panel.TVOutputProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TVOutputProperties,MODx.Panel,{

    isNativeType: true

    ,showOutputProperties: function(cb,rc,i) {

        const   formCmp = this.getComponent(1),
                tvPanel = Ext.getCmp('modx-panel-tv'),
                typeChanged = cb.hasOwnProperty('startValue') ? true : false ,
                type = cb.getValue(),
                tvId = this.config.record.id || '',
                listenerDefs = tvPanel.getListenerDefs(tvId, this),
                validatorDefs = tvPanel.getValidatorDefs(tvId, type),
                optsFieldset = Ext.getCmp('tv-output-opts-fs'),
                legacyOpts = Ext.get('modx-widget-props'),
                useLegacyLoader = tvPanel.useLegacyLoader(type, 'output')
        ;
        this.isNativeType = tvPanel.nativeTypes['output'].indexOf(type) !== -1 ? true : false ;

        if (useLegacyLoader) {
            let pu = legacyOpts.getUpdater();
            pu.loadScripts = true;
            optsFieldset.removeAll();
            try {
                pu.update({
                    url: MODx.config.connector_url
                    ,method: 'GET'
                    ,params: {
                           action: 'Element/TemplateVar/Renders/GetProperties'
                           ,context: 'mgr'
                           ,tv: this.config.record.id
                           ,type: type || 'default'
                    }
                    ,scripts: true
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
                       action: 'Element/TemplateVar/Configs/GetOutputPropertyConfigs'
                       ,context: 'mgr'
                       ,tv: this.config.record.id
                       ,type: type || 'default'
                       ,expandHelp: MODx.expandHelp
                   }
                   ,listeners: {
                        success: {
                            fn: function(r) {

                                // Make adjustments to returned config
                                if (r.hasOwnProperty('optsItems') && r.optsItems.length > 0) {
                                    tvPanel.updateFieldConfigs(r.optsItems, listenerDefs, validatorDefs);
                                    if(typeChanged){
                                        optsFieldset.removeAll();
                                    }
                                    optsFieldset.add(r.optsItems);
                                } else {
                                    if(typeChanged){
                                        optsFieldset.removeAll();
                                    }
                                }
                                if(typeChanged){
                                    formCmp.doLayout();
                                }
                            }
                            ,scope: this
                        }
                        ,failure: {
                            fn: function(r) {
                                console.error(`MODx.panel.TVOutputProperties: showOutputProperties failed to fetch the config for ${type} due to an error.`, r);
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
Ext.reg('modx-panel-tv-output-properties',MODx.panel.TVOutputProperties);

/**
 * @class MODx.grid.ElementSources
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-element-sources
 */
MODx.grid.ElementSources = function(config) {
    var src = new MODx.combo.MediaSource();
    src.getStore().load();

    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-element-sources'
        ,fields: ['context_key','source','name']
        ,showActionsColumn: false
        ,autoHeight: true
        ,primaryKey: 'id'
        ,columns: [{
            header: _('context')
            ,dataIndex: 'context_key'
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=context/update&key=' + v
                    ,target: '_blank'
                });
            }, scope: this }
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
