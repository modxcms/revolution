MODx.panel.Resource = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
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
            ,value: (config.record.content || config.record.ta) || ''
        },{
            id: 'modx-content-below'
            ,border: false
        }]
    };
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
            ,description: '<b>[[*id]]</b><br />'
            ,name: 'id'
            ,id: 'modx-resource-id'
            ,anchor: '97%'
            ,value: config.resource || config.record.id
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
                    ,description: '<b>[[*template]]</b><br />'+_('resource_template_help')
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
                }]
            },{
                columnWidth: .30
                ,layout: 'form'
                ,hideLabels: true
                ,labelWidth: 0
                ,border: false
                ,items: [{
                    xtype: 'xcheckbox'
                    ,boxLabel: _('resource_published')
                    ,description: '<b>[[*published]]</b><br />'+_('resource_published_help')
                    ,name: 'published'
                    ,id: 'modx-resource-published'
                    ,inputValue: 1
                    ,checked: parseInt(config.record.published)
                }]
            }]
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_pagetitle')
            ,description: '<b>[[*pagetitle]]</b><br />'+_('resource_pagetitle_help')
            ,name: 'pagetitle'
            ,id: 'modx-resource-pagetitle'
            ,maxLength: 255
            ,anchor: '75%'
            ,allowBlank: false
            ,enableKeyEvents: true
            ,listeners: {
                'keyup': {scope:this,fn:function(f,e) {
                    titlePrefix = MODx.request.a == MODx.action['resource/create'] ? _('new_document') : _('document');
                    Ext.getCmp('modx-resource-header').getEl().update('<h2>'+titlePrefix+': '+f.getValue()+'</h2>');
                }}
            }
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: '<b>[[*longtitle]]</b><br />'+_('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-resource-longtitle'
            ,maxLength: 255
            ,anchor: '75%'
            ,value: config.record.longtitle || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_description')
            ,description: '<b>[[*description]]</b><br />'+_('resource_description_help')
            ,name: 'description'
            ,id: 'modx-resource-description'
            ,maxLength: 255
            ,anchor: '75%'
            ,value: config.record.description || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: '<b>[[*alias]]</b><br />'+_('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-resource-alias'
            ,maxLength: 100
            ,anchor: '75%'
            ,value: config.record.alias || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: '<b>[[*link_attributes]]</b><br />'+_('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,id: 'modx-resource-link-attributes'
            ,maxLength: 255
            ,anchor: '75%'
            ,value: config.record.link_attributes || ''
            
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: '<b>[[*introtext]]</b><br />'+_('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-resource-introtext'
            ,grow: true
            ,anchor: '90%'
            ,value: config.record.introtext || ''
            
        },{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: '<b>[[*parent]]</b><br />'+_('resource_parent_help')
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
            xtype: 'hidden'
            ,name: 'parent-original'
            ,value: config.record.parent || 0
            ,id: 'modx-resource-parent-old-hidden'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_menutitle')
            ,description: '<b>[[*menutitle]]</b><br />'+_('resource_menutitle_help')
            ,name: 'menutitle'
            ,id: 'modx-resource-menutitle'
            ,maxLength: 255
            ,anchor: '70%'
            ,value: config.record.menutitle || ''
            
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('resource_menuindex')
            ,description: '<b>[[*menuindex]]</b><br />'+_('resource_menuindex_help')
            ,name: 'menuindex'
            ,id: 'modx-resource-menuindex'
            ,width: 60
            ,value: parseInt(config.record.menuindex) || 0
            
        },{
            xtype: 'xcheckbox'
            ,fieldLabel: _('resource_hide_from_menus')
            ,description: '<b>[[*hidemenu]]</b><br />'+_('resource_hide_from_menus_help')
            ,name: 'hidemenu'
            ,id: 'modx-resource-hidemenu'
            ,inputValue: 1
            ,checked: parseInt(config.record.hidemenu) || false
            
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
            ,value: (config.record.content || config.record.ta) || ''
        },{
            xtype: 'hidden'
            ,name: 'create-resource-token'
            ,id: 'modx-create-resource-token'
            ,value: config.record.create_resource_token || ''
        },{
            html: MODx.onDocFormRender, border: false
        }]
    });
    if (!MODx.config.manager_use_tabs) {
        it.push(ct);
    }
    
    var va = [];
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_folder')
        ,description: '<b>[[*isfolder]]</b><br />'+_('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-resource-isfolder'
        ,inputValue: 1
        ,checked: parseInt(config.record.isfolder) || 0
        
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_richtext')
        ,description: '<b>[[*richtext]]</b><br />'+_('resource_richtext_help')
        ,name: 'richtext'
        ,id: 'modx-resource-richtext'
        ,inputValue: 1
        ,checked: parseInt(config.record.richtext)
        
    },{
        xtype: 'xdatetime'
        ,fieldLabel: _('resource_publishedon')
        ,description: '<b>[[*publishedon]]</b><br />'+_('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-resource-publishedon'
        ,allowBlank: true
        ,dateFormat: MODx.config.manager_date_format
        ,timeFormat: MODx.config.manager_time_format
        ,dateWidth: 120
        ,timeWidth: 120
        ,value: config.record.publishedon
    });
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'xdatetime'
            ,fieldLabel: _('resource_publishdate')
            ,description: '<b>[[*pub_date]]</b><br />'+_('resource_publishdate_help')
            ,name: 'pub_date'
            ,id: 'modx-resource-pub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120
            ,value: config.record.pub_date
            
        });
    }
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'xdatetime'
            ,fieldLabel: _('resource_unpublishdate')
            ,description: '<b>[[*unpub_date]]</b><br />'+_('resource_unpublishdate_help')
            ,name: 'unpub_date'
            ,id: 'modx-resource-unpub-date'
            ,allowBlank: true
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120
            ,value: config.record.unpub_date
        });
    }
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_searchable')
        ,description: '<b>[[*searchable]]</b><br />'+_('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-resource-searchable'
        ,inputValue: 1
        ,checked: parseInt(config.record.searchable)
        
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: '<b>[[*cacheable]]</b><br />'+_('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-resource-cacheable'
        ,inputValue: 1
        ,checked: parseInt(config.record.cacheable)
        
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_syncsite')
        ,description: _('resource_syncsite_help')
        ,name: 'syncsite'
        ,id: 'modx-resource-syncsite'
        ,inputValue: 1
        ,checked: parseInt(config.record.syncsite) || true
        
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('deleted')
        ,description: '<b>[[*deleted]]</b>'
        ,name: 'deleted'
        ,id: 'modx-resource-deleted'
        ,inputValue: 1
        ,checked: parseInt(config.record.deleted) || false

    },{
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: '<b>[[*content_type]]</b><br />'+_('resource_content_type_help')
        ,name: 'content_type'
        ,hiddenName: 'content_type'
        ,id: 'modx-resource-content-type'
        ,anchor: '70%'
        ,value: config.record.content_type || 1
        
    },{
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: '<b>[[*content_dispo]]</b><br />'+_('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,id: 'modx-resource-content-dispo'
        ,anchor: '70%'
        ,value: config.record.content_dispo || 0
        
    },{
        xtype: 'modx-combo-class-map'
        ,fieldLabel: _('class_key')
        ,description: '<b>[[*class_key]]</b><br />'
        ,name: 'class_key'
        ,hiddenName: 'class_key'
        ,id: 'modx-resource-class-key'
        ,baseParams: { action: 'getList', parentClass: 'modResource' }
        ,allowBlank: false
        ,value: config.record.class_key || 'modDocument'
        ,anchor: '70%'

    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_uri_override')
        ,description: _('resource_uri_override_help')
        ,name: 'uri_override'
        ,value: 1
        ,checked: parseInt(config.record.uri_override) ? true : false
        ,id: 'modx-resource-uri-override'

    },{
        xtype: 'textfield'
        ,fieldLabel: _('resource_uri')
        ,description: '<b>[[*uri]]</b><br />'+_('resource_uri_help')
        ,name: 'uri'
        ,id: 'modx-resource-uri'
        ,maxLength: 255
        ,anchor: '70%'
        ,value: config.record.uri || ''
        ,hidden: !config.record.uri_override
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

    if (config.show_tvs) {
        it.push({
            xtype: 'modx-panel-resource-tv'
            ,collapsed: false
            ,resource: config.resource
            ,class_key: config.record.class_key || 'modDocument'
            ,template: config.record.template
            ,anchor: '100%'
            ,border: true
        });
    }
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
            ,'failure': {fn:this.failure,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    var ta = Ext.get('ta');
    if (ta) { ta.on('keydown',this.fieldChangeEvent,this); }
    this.on('ready',this.onReady,this);
    var urio = Ext.getCmp('modx-resource-uri-override');
    if (urio) { urio.on('check',this.freezeUri); }
    this.addEvents('tv-reset');
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    initialized: false
    ,defaultClassKey: 'modDocument'
    ,classLexiconKey: 'document'
    ,rteElements: 'ta'
    ,rteLoaded: false
    ,setup: function() {
        if (!this.initialized) { 
            this.getForm().setValues(this.config.record);
            pcmb = this.getForm().findField('parent-cmb');
            if (pcmb && Ext.isEmpty(this.config.record.parent_pagetitle)) {
                pcmb.setValue('');
            } else if (pcmb) {
                pcmb.setValue(this.config.record.parent_pagetitle+' ('+this.config.record.parent+')');
            }
            if (!Ext.isEmpty(this.config.record.pagetitle)) {
                Ext.getCmp('modx-resource-header').getEl().update('<h2>'+_(this.classLexiconKey)+': '+this.config.record.pagetitle+'</h2>');
            }
            this.defaultClassKey = this.config.record.class_key || this.defaultClassKey;
        }
        if (MODx.config.use_editor && MODx.loadRTE) {
            var f = this.getForm().findField('richtext');
            if (f && f.getValue() == 1 && !this.rteLoaded) {
                MODx.loadRTE(this.rteElements);
                this.rteLoaded = true;
            } else if (f && f.getValue() == 0 && this.rteLoaded) {
                if (MODx.unloadRTE) {
                    MODx.unloadRTE('ta');
                }
                this.rteLoaded = false;
            }
        }
        this.fireEvent('ready');
        this.initialized = true;

        MODx.fireEvent('ready');
        MODx.sleep(4); /* delay load event to allow FC rules to move before loading RTE */
        if (MODx.afterTVLoad) { MODx.afterTVLoad(); }
        this.fireEvent('load');
    }
    
    ,beforeSubmit: function(o) {
        var ta = Ext.get('ta');
        if (ta) {
            var v = ta.dom.value;
            var hc = Ext.getCmp('hiddenContent');
            if (hc) { hc.setValue(v); }
        }
        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) {
            Ext.apply(o.form.baseParams,{
                resource_groups: g.encodeModified()
            });
        }
        if (ta) {
            this.cleanupEditor();
        }
        if(this.getForm().baseParams.action == 'create') {
            Ext.getCmp('modx-button-save-resource').disable();
        }
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay')
        });
    }
    ,success: function(o) {
        var g = Ext.getCmp('modx-grid-resource-security');
        if (g) { g.getStore().commitChanges(); }
        var t = Ext.getCmp('modx-resource-tree');

        if (t) {
            var ctx = Ext.getCmp('modx-resource-context-key').getValue();
            var pa = Ext.getCmp('modx-resource-parent-hidden').getValue();
            var pao = Ext.getCmp('modx-resource-parent-old-hidden').getValue();
            var v = ctx+'_'+pa;
            var n = t.getNodeById(v);
            if(pa !== pao) {
                t.refresh();
                Ext.getCmp('modx-resource-parent-old-hidden').setValue(pa);
            } else {
                n.leaf = false;
                t.refreshNode(v,true);
            }
        }
        if (o.result.object.class_key != this.defaultClassKey && this.config.resource != '' && this.config.resource != 0) {
            location.href = location.href;
        } else {
            this.getForm().setValues(o.result.object);
            Ext.getCmp('modx-page-update-resource').config.preview_url = o.result.object.preview_url;
        }
    }
    ,failure: function(o) {
        if(this.getForm().baseParams.action == 'create') {
            Ext.getCmp('modx-button-save-resource').enable();
        }
    }

    ,freezeUri: function(cb) {
        var uri = Ext.getCmp('modx-resource-uri');
        if (!uri) { return false; }
        if (cb.checked) {
            uri.show();
        } else {
            uri.hide();
        }
    }
    
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-resource-template');
        if (!t) { return false; }
        if(t.getValue() !== t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
                if (e == 'yes') {
                    var nt = t.getValue();
                    t.setValue(this.config.record.template);
                    MODx.activePage.submitForm({
                        success: {fn:function(r) {
                            location.href = '?a='+MODx.action['resource/update']+'&id='+r.result.object.id+'&template='+nt+'&activeSave=1';
                        },scope:this}
                    },{
                        bypassValidCheck: true
                    },{
                        reloadOnly: true
                    });
                } else {
                    t.setValue(this.config.record.template);
                }
            },this);
        }
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