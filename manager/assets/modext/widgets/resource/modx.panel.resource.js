MODx.panel.Resource = function(config) {
    config = config || {};
    var ct = {
        title: _('resource_content')
        ,id: 'modx-resource-content'
        ,layout: 'form'
        ,bodyStyle: 'padding: 15px;'
        ,autoHeight: true
        ,collapsible: false
        ,items: [{
            id: 'modx-content-above'
            ,border: false
        },{
            xtype: 'textarea'
            ,name: 'ta'
            ,id: 'ta'
            ,hideLabel: true
            ,anchor: '97%'
            ,height: 400
            ,grow: false
        },{
            id: 'modx-content-below'
            ,border: false
        }]
    };
    delete rte;
    var it = [];
    it.push({
        title: _('createedit_document')
        ,id: 'modx-resource-settings'
        ,cls: 'modx-resource-tab'
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 15px 15px 15px 0;'
        ,autoHeight: true
        ,defaults: {
            border: false
            ,msgTarget: 'side'
            ,width: 400
        }
        ,items: [{
            xtype: (config.resource ? 'statictextfield' : 'hidden')
            ,fieldLabel: _('id')
            ,name: 'id'
            ,id: 'modx-resource-id'
            ,anchor: '97%'
            ,value: config.resource
            ,submitValue: true
        },{
            layout:'column'
            ,border: false
            ,anchor: '97%'
            ,items:[{
                columnWidth: .70
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,fieldLabel: _('resource_template')
                    ,description: _('resource_template_help')
                    ,name: 'template'
                    ,id: 'modx-resource-template'
                    ,anchor: '97%'
                    ,editable: false
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                    ,listeners: {
                        'select': {fn: this.templateWarning,scope: this}
                    }
                    ,value: config.record.template
                }]
            },{
                columnWidth: .30
                ,layout: 'form'
                ,hideLabels: true
                ,labelWidth: 0
                ,border: false
                ,items: [{
                    xtype: 'checkbox'
                    ,boxLabel: _('resource_published')
                    ,description: _('resource_published_help')
                    ,name: 'published'
                    ,id: 'modx-resource-published'
                    ,inputValue: 1
                    ,checked: MODx.config.publish_default == '1' ? true : false
                    
                }]
            }]
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_pagetitle')
            ,description: _('resource_pagetitle_help')
            ,name: 'pagetitle'
            ,id: 'modx-resource-pagetitle'
            ,maxLength: 255
            ,anchor: '75%'
            ,allowBlank: false
            ,enableKeyEvents: true
            ,listeners: {
                'keyup': {scope:this,fn:function(f,e) {
                    Ext.getCmp('modx-resource-header').getEl().update('<h2>'+_('document')+': '+f.getValue()+'</h2>');
                }}
            }
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: _('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-resource-longtitle'
            ,maxLength: 255
            ,anchor: '75%'
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_description')
            ,description: _('resource_description_help')
            ,name: 'description'
            ,id: 'modx-resource-description'
            ,maxLength: 255
            ,anchor: '75%'
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: _('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-resource-alias'
            ,maxLength: 100
            ,anchor: '75%'
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: _('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,id: 'modx-resource-link-attributes'
            ,maxLength: 255
            ,anchor: '75%'
            
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: _('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-resource-introtext'
            ,grow: true
            ,anchor: '90%'
            
        },{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: _('resource_parent_help')
            ,name: 'parent-cmb'
            ,id: 'modx-resource-parent'
            ,value: config.record.parent || 0
            ,anchor: '70%'
        },{
            xtype: 'hidden'
            ,name: 'parent'
            ,value: config.record.parent || 0
            ,id: 'modx-resource-parent-hidden'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_menutitle')
            ,description: _('resource_menutitle_help')
            ,name: 'menutitle'
            ,id: 'modx-resource-menutitle'
            ,maxLength: 255
            ,anchor: '70%'
            
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('resource_menuindex')
            ,description: _('resource_menuindex_help')
            ,name: 'menuindex'
            ,id: 'modx-resource-menuindex'
            ,width: 60
            
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('resource_hide_from_menus')
            ,description: _('resource_hide_from_menus_help')
            ,name: 'hidemenu'
            ,id: 'modx-resource-hidemenu'
            ,inputValue: 1
            ,checked: false
            
        },{
            xtype: 'hidden'
            ,name: 'type'
            ,value: 'document'                        
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,id: 'modx-resource-context-key'
            ,value: config.record.context_key || 'web'
        },{
            xtype: 'hidden'
            ,name: 'content'
            ,id: 'hiddenContent'
        },{
            html: MODx.onDocFormRender, border: false
        }]
    });
    if (!MODx.config.manager_use_tabs) {
        it.push(ct);
    }
    
    var va = [];
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-resource-isfolder'
        ,inputValue: 1
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_richtext')
        ,description: _('resource_richtext_help')
        ,name: 'richtext'
        ,id: 'modx-resource-richtext'
        ,inputValue: 1
        ,checked: MODx.config.richtext_default == '1' ? true : false
        
    },{
        xtype: 'xdatetime'
        ,fieldLabel: _('resource_publishedon')
        ,description: _('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-resource-publishedon'
        ,allowBlank: true
        ,dateFormat: MODx.config.manager_date_format
        ,timeFormat: MODx.config.manager_time_format
        ,dateWidth: 120
        ,timeWidth: 120
    });
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'xdatetime'
            ,fieldLabel: _('resource_publishdate')
            ,description: _('resource_publishdate_help')
            ,name: 'pub_date'
            ,id: 'modx-resource-pub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120
            
        });
    }
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'xdatetime'
            ,fieldLabel: _('resource_unpublishdate')
            ,description: _('resource_unpublishdate_help')
            ,name: 'unpub_date'
            ,id: 'modx-resource-unpub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120            
        });
    }
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-resource-searchable'
        ,inputValue: 1
        ,checked: MODx.config.search_default == '1' ? true : false
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: _('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-resource-cacheable'
        ,inputValue: 1
        ,checked: MODx.config.cache_default == '1' ? true : false
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_syncsite')
        ,description: _('resource_syncsite_help')
        ,name: 'syncsite'
        ,id: 'modx-resource-syncsite'
        ,inputValue: 1
        ,checked: true
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('deleted')
        ,name: 'deleted'
        ,id: 'modx-resource-deleted'
        ,inputValue: 1
        ,checked: false

    },{
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: _('resource_content_type_help')
        ,name: 'content_type'
        ,hiddenName: 'content_type'
        ,id: 'modx-resource-content-type'
        ,value: 1
        ,anchor: '70%'
        
    },{
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: _('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,id: 'modx-resource-content-dispo'
        ,anchor: '70%'
        
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class_key')
        ,name: 'class_key'
        ,id: 'modx-resource-class-key'
        ,allowBlank: false
        ,value: 'modDocument'
        ,anchor: '70%'
    });
    it.push({
        id: 'modx-page-settings'
        ,title: _('page_settings')
        ,cls: 'modx-resource-tab'
        ,layout: 'form'
        ,forceLayout: true
        ,deferredRender: false
        ,labelWidth: 200
        ,bodyStyle: 'padding: 15px 15px 15px 0;'
        ,autoHeight: true
        ,defaults: {
            border: false
            ,msgTarget: 'side'
        }
        ,items: va
    });
    
    it.push({
        xtype: 'modx-panel-resource-tv'
        ,collapsed: false
        ,resource: config.resource
        ,class_key: config.record.class_key
        ,template: config.record.template
        ,anchor: '100%'
    });
    if (config.access_permissions) {
        it.push({
            id: 'modx-resource-access-permissions'
            ,bodyStyle: 'padding: 15px;'
            ,autoHeight: true
            ,title: _('access_permissions')
            ,layout: 'form'
            ,anchor: '100%'
            ,items: [{
                html: '<p>'+_('resource_access_message')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-resource-security'
                ,preventRender: true
                ,resource: config.resource
                ,listeners: {
                    'afteredit': {fn:this.fieldChangeEvent,scope:this}
                }
            }]
        });
    };
    var its = [];
    its.push({
        html: '<h2>'+_('document_new')+'</h2>'
        ,id: 'modx-resource-header'
        ,cls: 'modx-page-header'
        ,border: false
        ,forceLayout: true
        ,anchor: '100%'
    });
    its.push(MODx.getPageStructure(it,{
        id:'modx-resource-tabs'
        ,forceLayout: true
        ,deferredRender: false
        ,collapsible: true
    }));
    
    if (MODx.config.manager_use_tabs) {
        ct.style = 'margin-top: 10px;';
        its.push(ct);
    }
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'modx-panel-resource'
        ,class_key: 'modResource'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,forceLayout: true
        ,items: its
        ,fileUpload: true
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    var ta = Ext.get('ta');
    if (ta) { ta.on('keydown',this.fieldChangeEvent,this); }
    /* to deal with combobox bug */
    setTimeout("Ext.getCmp('modx-panel-resource').onLoad();",1000);
    this.on('ready',this.onReady,this);
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    rteLoaded: false
    ,initialized: false
    ,defaultClassKey: 'modResource'
    ,onLoad: function() {
        this.getForm().setValues(this.config.record);
    }
    ,setup: function() {
        if (this.config.resource === '' || this.config.resource === 0 || this.initialized) {
            if (MODx.config.use_editor && MODx.loadRTE) {
                var f = this.getForm().findField('richtext');
                if (f && f.getValue() == 1 && !this.rteLoaded) {
                    MODx.loadRTE('ta');
                    this.rteLoaded = true;
                } else if (f && f.getValue() == 0 && this.rteLoaded) {
                    if (MODx.unloadRTE) {
                        MODx.unloadRTE('ta');
                    }
                    this.rteLoaded = false;
                }
            }
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.record.class_key
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    r.object.ta = r.object.content;
                    this.getForm().setValues(r.object);

                    Ext.getCmp('modx-resource-header').getEl().update('<h2>'+_('document')+': '+r.object.pagetitle+'</h2>');

                    if (r.object.richtext == 1 && MODx.config.use_editor == 1 && !this.rteLoaded) {
                        if (MODx.loadRTE && !this.rteLoaded) {
                            MODx.loadRTE('ta');
                        }
                        this.rteLoaded = true;
                    } else if (r.object.richtext == 0 && this.rteLoaded) {
                        if (MODx.unloadRTE) {
                            MODx.unloadRTE('ta');
                        }
                        this.rteLoaded = false;
                    }
                    this.defaultClassKey = r.object.class_key;
                    this.initialized = true;
                    this.fireEvent('ready',r);
                },scope:this}
            }
        });
    }
    
    ,beforeSubmit: function(o) {        
        var ta = Ext.get('ta');
        if (!ta) return false;
        
        var v = ta.dom.value;
        
        var hc = Ext.getCmp('hiddenContent');
        if (hc) { hc.setValue(v); }

        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) {
            Ext.apply(o.form.baseParams,{
                resource_groups: g.encodeModified()
            });
        }
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay')
        });
    }
    ,success: function(o) {
        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) { g.getStore().commitChanges(); }
        var t = Ext.getCmp('modx-resource-tree');

        this.getForm().setValues(o.result.object);
        if (t) {
            var ctx = Ext.getCmp('modx-resource-context-key').getValue();
            var pa = Ext.getCmp('modx-resource-parent-hidden').getValue();
            var v = ctx+'_'+pa;
            var n = t.getNodeById(v);
            n.leaf = false;
            t.refreshNode(v,true);
        }
        if (o.result.object.class_key != this.defaultClassKey && this.config.resource != '' && this.config.resource != 0) {
            location.href = location.href;
        }
        Ext.getCmp('modx-page-update-resource').config.preview_url = o.result.object.preview_url;
    }
    
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-resource-template');
        if (!t) { return false; }
        if(t.getValue() != t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
                if (e == 'yes') {
                    var tvpanel = Ext.getCmp('modx-panel-resource-tv');
                    if(tvpanel && tvpanel.body) {
                        this.tvum = tvpanel.body.getUpdater();
                        this.tvum.update({
                            url: 'index.php?a='+MODx.action['resource/tvs']
                            ,params: {
                                class_key: this.config.record.class_key
                                ,resource: (this.config.resource ? this.config.resource : 0)
                                ,template: t.getValue()
                            }
                            ,discardUrl: true
                            ,scripts: true
                            ,nocache: true
                        });
                    }
                    t.originalValue = t.getValue();
                } else {
                    t.reset();
                }
            },this);
        }
    }
    
    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-resource-which-editor').getValue();
            MODx.request.a = MODx.action['resource/update'];
            var u = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
            location.href = u;
        });
        this.submit();
    }    
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('ta');
            if (fld) { MODx.onSaveEditor(fld); }
        }
    }
});
Ext.reg('modx-panel-resource',MODx.panel.Resource);

var triggerDirtyField = function(fld) {
    Ext.getCmp('modx-panel-resource').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function() {
    triggerDirtyField(Ext.getCmp('ta'));
};
MODx.fireResourceFormChange = function(f,nv,ov) {
    Ext.getCmp('modx-panel-resource').fireEvent('fieldChange');
};