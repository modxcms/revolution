/**
 * @class MODx.panel.Resource
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-resource
 */
MODx.panel.Resource = function(config) {
    config = config || {};
    
    var rte = [{
        xtype: 'textarea'
        ,name: 'ta'
        ,id: 'ta'
        ,hideLabel: true
        ,width: '97%'
        ,height: 400
        ,grow: false
    }];
    if (MODx.config.use_editor) {
        rte.push({
            html: '<button class="modx-richtext-content-toggle" id="ta-toggle">'+_('toggle_richtext')+'</button>'
            ,border: false
        });
    }
    var ct = {
        title: _('resource_content')
        ,id: 'modx-resource-content'
        ,layout: 'form'
        ,bodyStyle: 'padding: 1.5em;'
        ,autoHeight: true
        ,collapsible: true
        ,titleCollapse: true
        ,items: rte
    };
    delete rte;
    var it = [];
    it.push({
        title: _('createedit_document')
        ,id: 'modx-resource-settings'
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 1.5em;'
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
            ,value: config.resource
            ,submitValue: true
        },{
            layout:'column'
            ,border: false
            ,width: '100%'
            ,items:[{
                columnWidth: .55
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,fieldLabel: _('resource_template')
                    ,description: _('resource_template_help')
                    ,name: 'template'
                    ,id: 'modx-resource-template'
                    ,width: 300
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
                columnWidth: .45
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
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_description')
            ,description: _('resource_description_help')
            ,name: 'description'
            ,id: 'modx-resource-description'
            ,maxLength: 255
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: _('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-resource-alias'
            ,maxLength: 100
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: _('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,id: 'modx-resource-link-attributes'
            ,maxLength: 255
            
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: _('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-resource-introtext'
            ,grow: true
            
        },{
            xtype: 'modx-field-parent-change'
            ,fieldLabel: _('resource_parent')
            ,description: _('resource_parent_help')
            ,name: 'parent-cmb'
            ,id: 'modx-resource-parent'
            ,value: config.record.parent || 0
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
        ,checked: true
        
    },{
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishedon')
        ,description: _('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-resource-publishedon'
        ,allowBlank: true
        ,dateWidth: 120
        ,timeWidth: 120
    });
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'datetimefield'
            ,fieldLabel: _('resource_publishdate')
            ,description: _('resource_publishdate_help')
            ,name: 'pub_date'
            ,id: 'modx-resource-pub-date'
            ,allowBlank: true
            ,dateWidth: 120
            ,timeWidth: 120
            
        });
    }
    if (MODx.config.publish_document) {
        va.push({
            xtype: 'datetimefield'
            ,fieldLabel: _('resource_unpublishdate')
            ,description: _('resource_unpublishdate_help')
            ,name: 'unpub_date'
            ,id: 'modx-resource-unpub-date'
            ,allowBlank: true
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
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: _('resource_content_type_help')
        ,name: 'content_type'
        ,hiddenName: 'content_type'
        ,id: 'modx-resource-content-type'
        ,width: 300
        ,value: 1
        
    },{
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: _('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,id: 'modx-resource-content-dispo'
        ,width: 300
        
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class_key')
        ,name: 'class_key'
        ,id: 'modx-resource-class-key'
        ,allowBlank: false
        ,value: 'modDocument'
        ,width: 300
    });
    it.push({
        id: 'modx-page-settings'
        ,title: _('page_settings')
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 1.5em;'
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
    });
    if (config.access_permissions) {
        it.push({
            id: 'modx-resource-access-permissions'
            ,bodyStyle: 'padding: 1.5em;'
            ,autoHeight: true
            ,title: _('access_permissions')
            ,layout: 'form'
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
    });
    its.push(MODx.getPageStructure(it,{id:'modx-resource-tabs'}));
    
    if (MODx.config.manager_use_tabs) {
        ct.style = 'margin-top: 1.0em;';
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
        ,items: its
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Resource.superclass.constructor.call(this,config);
    Ext.get('ta').on('keydown',this.fieldChangeEvent,this);
    /* to deal with combobox bug */
    setTimeout("Ext.getCmp('modx-panel-resource').onLoad();",1000);
    
    
};
Ext.extend(MODx.panel.Resource,MODx.FormPanel,{
    rteLoaded: false
    ,onLoad: function() {
        this.getForm().setValues(this.config.record);
    }
    ,setup: function() {
        if (this.config.resource === '' || this.config.resource === 0) {
            if (MODx.config.use_editor && MODx.loadRTE) MODx.loadRTE('ta');
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
                    
                    if (r.object.richtext && MODx.config.use_editor && MODx.loadRTE && !this.rteLoaded) {
                    	MODx.loadRTE('ta');
                        this.rteLoaded = true;
                    }
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
    
    ,beforeSubmit: function(o) {        
        var v = Ext.get('ta').dom.value;
        Ext.getCmp('hiddenContent').setValue(v);
        
        var g = Ext.getCmp('modx-grid-resource-security');
        Ext.apply(o.form.baseParams,{
            resource_groups: g.encodeModified()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }

    ,success: function(o) {
        Ext.getCmp('modx-grid-resource-security').getStore().commitChanges();
        var t = parent.Ext.getCmp('modx-resource-tree');
        var ctx = Ext.getCmp('modx-resource-context-key').getValue();
        var pa = Ext.getCmp('modx-resource-parent-hidden').getValue();
        t.refreshNode(ctx+'_'+pa,true);
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
            MODx.onSaveEditor(fld);
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