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
            action: 'element/template/get'
        }
        ,id: 'modx-panel-template'
		,cls: 'container form-with-labels'
        ,class_key: 'modTemplate'
        ,template: ''
        ,bodyStyle: ''
        ,items: [{
            html: _('template_new')
            ,id: 'modx-template-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('template_title')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-template-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('template_msg')+'</p>'
                ,id: 'modx-template-msg'
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
                        ,id: 'modx-template-id'
                        ,value: config.template
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-template-props'
                        ,value: config.record.props || null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')+'<span class="required">*</span>'
                        ,description: MODx.expandHelp ? '' : _('template_desc_name')
                        ,name: 'templatename'
                        ,id: 'modx-template-templatename'
                        ,anchor: '100%'
                        ,maxLength: 100
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,value: config.record.templatename
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                var title = Ext.util.Format.stripTags(f.getValue());
                                title = _('template')+': '+Ext.util.Format.htmlEncode(title);
                                if (MODx.request.a !== 'element/template/create' && MODx.perm.tree_show_element_ids === 1) {
                                    title = title+ ' <small>('+this.config.record.id+')</small>';
                                }

                                Ext.getCmp('modx-template-header').getEl().update(title);

                                MODx.setStaticElementPath('template');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-templatename'
                        ,html: _('template_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('template_desc')
                        ,description: MODx.expandHelp ? '' : _('template_desc_description')
                        ,name: 'description'
                        ,id: 'modx-template-description'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-description'
                        ,html: _('template_desc_description')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-browser'
                        ,browserEl: 'modx-browser'
                        ,fieldLabel: _('static_file')
                        ,description: MODx.expandHelp ? '' : _('static_file_msg')
                        ,name: 'static_file'
                        ,source: config.record.source != null ? config.record.source : MODx.config.default_media_source
                        ,openTo: config.record.openTo || ''
                        ,id: 'modx-template-static-file'
                        ,triggerClass: 'x-form-code-trigger'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.static_file || ''
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                        ,validator: function(value){
                            if (Ext.getCmp('modx-template-static').getValue() === true) {
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
                        ,forId: 'modx-template-static-file'
                        ,id: 'modx-template-static-file-help'
                        ,html: _('static_file_msg')
                        ,cls: 'desc-under'
                        ,hidden: !config.record['static']
                        ,hideMode: 'offsets'
                    },{
                        html: MODx.onTempFormRender
                        ,border: false
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('template_icon')
                        ,description: MODx.expandHelp ? '' : _('template_icon_description')
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
                        ,html: _('template_icon_description')
                        ,cls: 'desc-under'
                    }]
                },{
                    columnWidth: .4
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('template_desc_category')
                        ,name: 'category'
                        ,id: 'modx-template-category'
                        ,anchor: '100%'
                        ,value: config.record.category || 0
                        ,listeners: {
                            'afterrender': {scope:this,fn:function(f,e) {
                                setTimeout(function(){
                                    MODx.setStaticElementPath('template');
                                }, 200);
                            }}
                            ,'change': {scope:this,fn:function(f,e) {
                                MODx.setStaticElementPath('template');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-category'
                        ,html: _('template_desc_category')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('template_lock')
                        ,description: MODx.expandHelp ? '' : _('template_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-template-locked'
                        ,inputValue: 1
                        ,checked: config.record.locked || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-locked'
                        ,html: _('template_lock_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('clear_cache_on_save')
                        ,description: MODx.expandHelp ? '' : _('clear_cache_on_save_msg')
                        ,hideLabel: true
                        ,name: 'clearCache'
                        ,id: 'modx-template-clear-cache'
                        ,inputValue: 1
                        ,checked: Ext.isDefined(config.record.clearCache) || true
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-clear-cache'
                        ,html: _('clear_cache_on_save_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,hideLabel: true
                        ,boxLabel: _('is_static')
                        ,description: MODx.expandHelp ? '' : _('is_static_msg')
                        ,name: 'static'
                        ,id: 'modx-template-static'
                        ,inputValue: 1
                        ,checked: config.record['static'] || false
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-static'
                        ,id: 'modx-template-static-help'
                        ,html: _('is_static_msg')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'modx-combo-source'
                        ,fieldLabel: _('static_source')
                        ,description: MODx.expandHelp ? '' : _('static_source_msg')
                        ,name: 'source'
                        ,id: 'modx-template-static-source'
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
                        ,forId: 'modx-template-static-source'
                        ,id: 'modx-template-static-source-help'
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
					,fieldLabel: _('template_code')
					,name: 'content'
					,id: 'modx-template-content'
					,anchor: '100%'
					,height: 400
					,value: config.record.content || ''
				}]
			}]
        },{
            xtype: 'modx-panel-element-properties'
            ,preventRender: true
            ,collapsible: true
            ,elementPanel: 'modx-panel-template'
            ,elementId: config.template
            ,elementType: 'modTemplate'
            ,record: config.record
        },{
            title: _('template_variables')
            ,itemId: 'form-template'
            ,defaults: { autoHeight: true }
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('template_tv_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
               xtype: 'modx-grid-template-tv'
			   ,cls:'main-wrapper'
               ,preventRender: true
               ,anchor: '100%'
               ,template: config.template
               ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afterEdit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
               }
            }]
        }],{
            id: 'modx-template-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'failure': {fn:this.failure,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'failureSubmit': {
                fn: function () {
                    this.showErroredTab(['modx-template-form'], 'modx-template-tabs')
                },
                scope: this
            }
        }
    });
    MODx.panel.Template.superclass.constructor.call(this,config);
    var isStatic = Ext.getCmp('modx-template-static');
    if (isStatic) { isStatic.on('check',this.toggleStaticFile); }
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{
    initialized: false
    ,setup: function() {

        if (!this.initialized) {
            /*
                The itemId (not id) of each form tab to be included/excluded; these correspond to the
                keys in each tab component's items property
            */
            this.errorHandlingTabs = ['modx-template-form'];
            this.errorHandlingIgnoreTabs = ['modx-panel-element-properties','form-template'];

            this.getForm().setValues(this.config.record);
        }

        if (this.initialized) { this.clearDirty(); return true; }

        if (!Ext.isEmpty(this.config.record.templatename)) {
            var title = _('template') + ': ' + Ext.util.Format.htmlEncode(this.config.record.templatename);
            if (MODx.perm.tree_show_element_ids === 1) {
                title = title+ ' <small>('+this.config.record.id+')</small>';
            }
            Ext.getCmp('modx-template-header').getEl().update(title);
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
        var browser = Ext.getCmp('modx-template-static-file')
            ,source = Ext.getCmp('modx-template-static-source').getValue();

        browser.config.source = source;
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
    ,toggleStaticFile: function(cb) {
        var flds = ['modx-template-static-file','modx-template-static-file-help','modx-template-static-source','modx-template-static-source-help'];
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
Ext.reg('modx-panel-template',MODx.panel.Template);
