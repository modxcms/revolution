/**
 * @class MODx.panel.Static
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-static
 */
MODx.panel.Static = function(config) {
    config = config || {};
    
    var oc = function(f,nv,ov) {
        Ext.getCmp('modx-panel-static').fireEvent('fieldChange');
    };
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'modx-panel-static'
        ,class_key: 'modStaticResource'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('static_resource_new')+'</h2>'
            ,id: 'modx-static-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('resource_settings')
                    ,defaults: { border: false ,msgTarget: 'side' }
                    ,items: [{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,value: config.resource
                        ,id: 'modx-symlink-id'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_pagetitle')
                        ,description: _('resource_pagetitle_help')
                        ,name: 'pagetitle'
                        ,id: 'modx-static-pagetitle'
                        ,width: 300
                        ,maxLength: 255
                        ,allowBlank: false
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_longtitle')
                        ,description: _('resource_longtitle_help')
                        ,name: 'longtitle'
                        ,id: 'modx-static-longtitle'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_description')
                        ,description: _('resource_description_help')
                        ,name: 'description'
                        ,id: 'modx-static-description'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_alias')
                        ,description: _('resource_alias_help')
                        ,name: 'alias'
                        ,id: 'modx-static-alias'
                        ,width: 300
                        ,maxLength: 100
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_link_attributes')
                        ,description: _('resource_link_attributes_help')
                        ,name: 'link_attributes'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'modx-combo-browser'
                        ,browserEl: 'modx-browser'
                        ,prependPath: false
                        ,prependUrl: false
                        ,hideFiles: true
                        ,fieldLabel: _('static_resource')
                        ,name: 'content'
                        ,id: 'modx-static-content'
                        ,width: 300
                        ,maxLength: 255
                        ,value: ''
                        ,listeners: {
                            'select':{fn:function(data) {
                                if (data.url.substring(0,1) == '/') {
                                    Ext.getCmp('modx-static-content').setValue(data.url.substring(1));
                                }   
                            },scope:this}
                        }
                        
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('resource_summary')
                        ,description: _('resource_summary_help')
                        ,name: 'introtext'
                        ,id: 'modx-static-introtext'
                        ,width: 300
                        ,grow: true
                        
                    },{
                        xtype: 'modx-combo-template'
                        ,fieldLabel: _('resource_template')
                        ,description: _('resource_template_help')
                        ,name: 'template'
                        ,id: 'modx-static-template'
                        ,width: 300
                        ,baseParams: {
                            action: 'getList'
                            ,combo: '1'
                        }
                        ,listeners: {
                            'select': {fn: this.templateWarning,scope: this}
                        }
                        ,value: config.record.template
                    },{
                        xtype: 'modx-field-parent-change'
                        ,fieldLabel: _('resource_parent')
                        ,description: _('resource_parent_help')
                        ,name: 'parent-cmb'
                        ,editable: false
                        ,id: 'modx-static-parent'
                        ,width: 300
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
                        ,id: 'modx-static-menutitle'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'numberfield'
                        ,fieldLabel: _('resource_menuindex')
                        ,description: _('resource_menuindex_help')
                        ,name: 'menuindex'
                        ,id: 'modx-static-menuindex'
                        ,width: 60
                        
                    },{
                        xtype: 'checkbox'
                        ,fieldLabel: _('resource_hide_from_menus')
                        ,description: _('resource_hide_from_menus_help')
                        ,name: 'hidemenu'
                        ,inputValue: 1
                        ,checked: false
                        
                    },{
                        xtype: 'hidden'
                        ,name: 'type'
                        ,value: 'document'                        
                    },{
                        xtype: 'hidden'
                        ,name: 'context_key'
                        ,id: 'modx-static-context-key'
                        ,value: 'web'
                    }]
                },{
                    xtype: 'modx-panel-resource-tv'
                    ,resource: config.resource
                    ,class_key: config.record.class_key
                    ,template: config.record.template
                    
                },(config.access_permissions ? {
                    id: 'modx-resource-access-permissions'
                    ,collapsed: false
                    ,title: _('access_permissions')
                    ,layout: 'form'
                    ,items: [{
                        html: '<p>'+_('resource_access_message')+'</p>'
                    },{
                        xtype: 'modx-grid-resource-security'
                        ,preventRender: true
                        ,resource: config.resource
                        ,listeners: {
                            'afteredit': {fn:this.fieldChangeEvent,scope:this}
                        }
                    }]
                } : {})]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    MODx.panel.Static.superclass.constructor.call(this,config);
    setTimeout("Ext.getCmp('modx-panel-static').onLoad();",1000);
};
Ext.extend(MODx.panel.Static,MODx.FormPanel,{
    onLoad: function() {
        this.getForm().setValues(this.config.record);
    }
    ,setup: function() {
        if (this.config.resource === '' || this.config.resource === 0) {
            this.fireEvent('ready');
            return false;
        }
        Ext.Ajax.request({
            url: MODx.config.connectors_url+'resource/index.php'
            ,params: {
                action: 'get'
                ,id: this.config.resource
                ,class_key: this.config.record.class_key
            }
            ,scope: this
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                if (r.success) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    r.object['parent-cmb'] = r.object.parent;
                    
                    Ext.getCmp('modx-static-settings-fp').getForm().setValues(r.object);                    
                    Ext.getCmp('modx-static-header').getEl().update('<h2>'+_('static_resource')+': '+r.object.pagetitle+'</h2>');
                    
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
                } else { MODx.form.Handler.errorJSON(r); }
            }
        });
    }
    ,beforeSubmit: function(o) {
        var vs = Ext.getCmp('modx-static-settings-fp').getForm().getValues();
        Ext.applyIf(vs,{
            isfolder: 0
            ,richtext: 0
            ,published: 0
            ,searchable: 0
            ,cacheable: 0
            ,syncsite: 0            
        });
        Ext.apply(o.form.baseParams,vs);
        
        var g = Ext.getCmp('modx-grid-resource-security');
        Ext.apply(o.form.baseParams,{
            resource_groups: g.encodeModified()
        });
    }

    ,success: function(o) {
        Ext.getCmp('modx-grid-resource-security').getStore().commitChanges();
        var t = parent.Ext.getCmp('modx_resource_tree');
        var ctx = Ext.getCmp('modx-static-context-key').getValue();
        var pa = Ext.getCmp('modx-static-parent').getValue();
        t.refreshNode(ctx+'_'+pa,true);
    }
    
    
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-static-template');
        if (!t) { return false; }
        /* if selection isn't the current value (originalValue), then show dialog */
        if(t.getValue() != t.originalValue) {
            Ext.Msg.confirm(_('warning'), _('resource_change_template_confirm'), function(e) {
                if (e == 'yes') {
                    var tvpanel = Ext.getCmp('modx-panel-resource-tv');
                    if(tvpanel && tvpanel.body) {
                        /* update the Template Variables tab */
                        this.tvum = tvpanel.body.getUpdater();
                        this.tvum.update({
                            url: 'index.php?a='+MODx.action['resource/tvs']
                            ,params: {
                                class_key: this.config.record.class_key
                                ,resource: this.config.resource
                                ,template: t.getValue()
                            }
                            ,discardUrl: true
                            ,scripts: true
                            ,nocache: true
                        });
                    }
                    t.originalValue = t.getValue(); /* so that the next reset will work logically */
                } else {
                    t.reset();
                }
            },this);
        }
    }
});
Ext.reg('modx-panel-static',MODx.panel.Static);


/* global accessor for TV dynamic fields */
var triggerDirtyField = function(fld) {
    Ext.getCmp('modx-panel-static').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function(i) {
    triggerDirtyField(Ext.getCmp('ta'));
}


MODx.loadAccordionPanels = function() {
    var va = [];
    var oc = function(f,nv,ov) {
        Ext.getCmp('modx-panel-static').fireEvent('fieldChange');
    };
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-static-isfolder'
        ,inputValue: 1
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_published')
        ,description: _('resource_published_help')
        ,name: 'published'
        ,id: 'modx-static-published'
        ,inputValue: 1
        ,checked: MODx.config.publish_default == '1' ? true : false
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishedon')
        ,description: _('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-static-publishedon'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
    },(MODx.config.publish_document ? {
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishdate')
        ,description: _('resource_publishdate_help')
        ,name: 'pub_date'
        ,id: 'modx-static-pub-date'
        ,format: 'd-m-Y H:i:s'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    }:{}),(MODx.config.publish_document ? {
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_unpublishdate')
        ,description: _('resource_unpublishdate_help')
        ,name: 'unpub_date'
        ,id: 'modx-static-unpub-date'
        ,format: 'd-m-Y H:i:s'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    }:{}),{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-static-searchable'
        ,inputValue: 1
        ,checked: MODx.config.search_default == '1' ? true : false
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: _('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-static-cacheable'
        ,inputValue: 1
        ,checked: true
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'hidden'
        ,name: 'class_key'
        ,id: 'modx-static-class-key'
        ,value: 'modStaticResource'
        
    },{
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: _('resource_content_type_help')
        ,name: 'content_type'
        ,id: 'modx-static-content-type'
        ,width: 100
        ,value: 1
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: _('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,id: 'modx-static-content-dispo'
        ,width: 100
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
        
    },{
        xtype: 'textfield'
        ,fieldLabel: _('class_key')
        ,name: 'class_key'
        ,id: 'modx-static-class-key'
        ,allowBlank: false
        ,value: 'modStaticResource'    
        ,width: 150
        ,listeners: {
            'focus': {fn:oc,scope:this}
        }
    });    
    return [{
        title: _('page_settings')
        ,id: 'modx-static-page-settings'
        ,items: [{
            xtype: 'modx-formpanel'
            ,bodyStyle: 'padding: .3em;'
            ,id: 'modx-static-settings-fp'
            ,labelWidth: 90
            ,items: va
            ,cls: 'none'
        }]
    }];
};