/**
 * @class MODx.panel.WebLink
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-weblink
 */
MODx.panel.WebLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'resource/index.php'
        ,baseParams: {}
        ,id: 'modx-panel-weblink'
        ,class_key: 'modWebLink'
        ,resource: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('weblink_new')+'</h2>'
            ,id: 'modx-weblink-header'
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
                        ,id: 'modx-weblink-id'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_pagetitle')
                        ,description: _('resource_pagetitle_help')
                        ,name: 'pagetitle'
                        ,id: 'modx-weblink-pagetitle'
                        ,width: 300
                        ,maxLength: 255
                        ,allowBlank: false
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_longtitle')
                        ,description: _('resource_longtitle_help')
                        ,name: 'longtitle'
                        ,id: 'modx-weblink-longtitle'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_description')
                        ,description: _('resource_description_help')
                        ,name: 'description'
                        ,id: 'modx-weblink-description'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_alias')
                        ,description: _('resource_alias_help')
                        ,name: 'alias'
                        ,id: 'modx-weblink-alias'
                        ,width: 300
                        ,maxLength: 100
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('resource_link_attributes')
                        ,description: _('resource_link_attributes_help')
                        ,name: 'link_attributes'
                        ,id: 'modx-weblink-link-attributes'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('weblink')
                        ,description: _('weblink_help')
                        ,name: 'content'
                        ,id: 'modx-weblink-content'
                        ,width: 300
                        ,maxLength: 255
                        ,value: 'http://'
                        ,allowBlank: false
                        
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('resource_summary')
                        ,description: _('resource_summary_help')
                        ,name: 'introtext'
                        ,id: 'modx-weblink-introtext'
                        ,width: 300
                        ,grow: true
                        
                    },{
                        xtype: 'modx-combo-template'
                        ,fieldLabel: _('resource_template')
                        ,description: _('resource_template_help')
                        ,name: 'template'
                        ,id: 'modx-weblink-template'
                        ,editable: false
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
                        ,id: 'modx-weblink-parent'
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
                        ,id :'modx-weblink-menutitle'
                        ,width: 300
                        ,maxLength: 255
                        
                    },{
                        xtype: 'numberfield'
                        ,fieldLabel: _('resource_menuindex')
                        ,description: _('resource_menuindex_help')
                        ,name: 'menuindex'
                        ,id: 'modx-weblink-menuindex'
                        ,width: 60
                        
                    },{
                        xtype: 'checkbox'
                        ,fieldLabel: _('resource_hide_from_menus')
                        ,description: _('resource_hide_from_menus_help')
                        ,name: 'hidemenu'
                        ,id: 'modx-weblink-hidemenu'
                        ,inputValue: 1
                        ,checked: false
                        
                    },{
                        xtype: 'hidden'
                        ,name: 'type'
                        ,value: 'document'                        
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
    MODx.panel.WebLink.superclass.constructor.call(this,config);
    setTimeout("Ext.getCmp('modx-panel-weblink').onLoad();",1000);
};
Ext.extend(MODx.panel.WebLink,MODx.FormPanel,{
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
                    r.object['parent-cmb'] = r.object['parent'];
                    
                    Ext.getCmp('modx-weblink-settings-fp').getForm().setValues(r.object);
                    Ext.getCmp('modx-weblink-header').getEl().update('<h2>'+_('weblink')+': '+r.object.pagetitle+'</h2>');
                    
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
                } else { MODx.form.Handler.errorJSON(r); }
            }
        });
    }
    ,beforeSubmit: function(o) {    
        var vs = Ext.getCmp('modx-weblink-settings-fp').getForm().getValues();
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
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }

    ,success: function(o) {
        Ext.getCmp('modx-grid-resource-security').getStore().commitChanges();
        var t = parent.Ext.getCmp('modx_resource_tree');
        var ctx = Ext.getCmp('modx-weblink-context-key').getValue();
        var pa = Ext.getCmp('modx-weblink-parent').getValue();
        t.refreshNode(ctx+'_'+pa,true);
    }
    
    ,templateWarning: function() {
        var t = Ext.getCmp('modx-weblink-template');
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
                                ,resource: this.config.resource
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
});
Ext.reg('modx-panel-weblink',MODx.panel.WebLink);

var triggerDirtyField = function(fld) {
    Ext.getCmp('modx-panel-weblink').fieldChangeEvent(fld);
};
MODx.triggerRTEOnChange = function(i) {
    triggerDirtyField(Ext.getCmp('ta'));
};

MODx.fireResourceFormChange = function(f,nv,ov) {
    Ext.getCmp('modx-panel-static').fireEvent('fieldChange');
};
MODx.loadAccordionPanels = function() {
    var va = [];
    var oc = MODx.fireResourceFormChange;
    va.push({
        xtype: 'checkbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-weblink-isfolder'
        ,inputValue: 1
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_published')
        ,description: _('resource_published_help')
        ,name: 'published'
        ,id: 'modx-weblink-published'
        ,inputValue: 1
        ,checked: MODx.config.publish_default == '1' ? true : false
        
    },{
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishedon')
        ,description: _('resource_publishedon_help')
        ,name: 'publishedon'
        ,id: 'modx-weblink-pubishedon'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        
    },(MODx.config.publish_document ? {
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_publishdate')
        ,description: _('resource_publishdate_help')
        ,name: 'pub_date'
        ,id: 'modx-weblink-pub-date'
        ,format: 'd-m-Y H:i:s'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        
    }:{}),(MODx.config.publish_document ? {
        xtype: 'datetimefield'
        ,fieldLabel: _('resource_unpublishdate')
        ,description: _('resource_unpublishdate_help')
        ,name: 'unpub_date'
        ,id: 'modx-weblink-unpub-date'
        ,format: 'd-m-Y H:i:s'
        ,allowBlank: true
        ,dateWidth: 80
        ,timeWidth: 80
        
    }:{}),{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-weblink-searchable'
        ,inputValue: 1
        ,checked: MODx.config.search_default == '1' ? true : false
        
    },{
        xtype: 'checkbox'
        ,fieldLabel: _('resource_syncsite')
        ,description: _('resource_syncsite_help')
        ,name: 'syncsite'
        ,id: 'modx-weblink-syncsite'
        ,inputValue: 1
        ,checked: true
        
    },{
        xtype: 'textfield'
        ,name: 'class_key'
        ,fieldLabel: _('class_key')
        ,id: 'modx-weblink-class-key'
        ,value: 'modWebLink'
        ,width: 150
        
    },{
        xtype: 'hidden'
        ,name: 'context_key'
        ,id: 'modx-weblink-context-key'
        ,value: 'web'
    });
    return [{
        title: _('page_settings')
        ,id: 'modx-weblink-page-settings'
        ,items: [{
            xtype: 'modx-formpanel'
            ,bodyStyle: 'padding: .3em;'
            ,id: 'modx-weblink-settings-fp'
            ,labelWidth: 90
            ,items: va
            ,cls: 'none'
        }]
    }];
};