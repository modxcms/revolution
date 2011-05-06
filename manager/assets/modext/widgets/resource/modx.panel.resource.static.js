/**
 * @class MODx.panel.Static
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-static
 */
MODx.panel.Static = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    
    var it = [];
    it.push({
        title: _('createedit_static')
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
            xtype: 'hidden'
            ,name: 'id'
            ,value: config.resource || config.record.id
            ,id: 'modx-resource-id'
            ,anchor: '97%'
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
                    ,checked: config.record.published
                    
                }]
            }]
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_pagetitle')
            ,description: '<b>[[*pagetitle]]</b><br />'+_('resource_pagetitle_help')
            ,name: 'pagetitle'
            ,id: 'modx-resource-pagetitle'
            ,maxLength: 255
            ,anchor: '90%'
            ,allowBlank: false
            ,value: config.record.pagetitle
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_longtitle')
            ,description: '<b>[[*longtitle]]</b><br />'+_('resource_longtitle_help')
            ,name: 'longtitle'
            ,id: 'modx-resource-longtitle'
            ,maxLength: 255
            ,anchor: '90%'
            ,value: config.record.longtitle || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_description')
            ,description: '<b>[[*description]]</b><br />'+_('resource_description_help')
            ,name: 'description'
            ,id: 'modx-resource-description'
            ,maxLength: 255
            ,anchor: '90%'
            ,value: config.record.description || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_alias')
            ,description: '<b>[[*alias]]</b><br />'+_('resource_alias_help')
            ,name: 'alias'
            ,id: 'modx-resource-alias'
            ,maxLength: 100
            ,anchor: '90%'
            ,value: config.record.alias || ''
            
        },{
            xtype: 'textfield'
            ,fieldLabel: _('resource_link_attributes')
            ,description: '<b>[[*link_attributes]]</b><br />'+_('resource_link_attributes_help')
            ,name: 'link_attributes'
            ,maxLength: 255
            ,anchor: '90%'
            ,value: config.record.link_attributes || ''
            
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'modx-browser'
            ,prependPath: false
            ,prependUrl: false
            ,hideFiles: true
            ,fieldLabel: _('static_resource')
            ,description: '<b>[[*content]]</b>'
            ,name: 'content'
            ,id: 'modx-resource-content'
            ,maxLength: 255
            ,anchor: '95%'
            ,value: (config.record.content || config.record.ta) || ''
            ,openTo: config.record.openTo
            ,listeners: {
                'select':{fn:function(data) {
                    var str = data.fullRelativeUrl;
                    if (MODx.config.base_url != '/') {
                        str = str.replace(MODx.config.base_url,'');
                    }
                    if (str.substring(0,1) == '/') { str = str.substring(1); }
                    Ext.getCmp('modx-resource-content').setValue(str);
                    this.markDirty();
                },scope:this}
            }
            
        },{
            xtype: 'textarea'
            ,fieldLabel: _('resource_summary')
            ,description: '<b>[[*introtext]]</b><br />'+_('resource_summary_help')
            ,name: 'introtext'
            ,id: 'modx-resource-introtext'
            ,anchor: '90%'
            ,grow: true
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
            ,anchor: '90%'
            
        },{
            xtype: 'numberfield'
            ,fieldLabel: _('resource_menuindex')
            ,description: '<b>[[*menuindex]]</b><br />'+_('resource_menuindex_help')
            ,name: 'menuindex'
            ,id: 'modx-resource-menuindex'
            ,width: 60
            ,value: config.record.menuindex || 0
            
        },{
            xtype: 'xcheckbox'
            ,fieldLabel: _('resource_hide_from_menus')
            ,description: '<b>[[*hidemenu]]</b><br />'+_('resource_hide_from_menus_help')
            ,name: 'hidemenu'
            ,inputValue: 1
            ,anchor: '75%'
            ,checked: config.record.hidemenu || 0
            
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
            ,name: 'create-resource-token'
            ,id: 'modx-create-resource-token'
            ,value: config.record.create_resource_token || ''
        },{
            html: MODx.onDocFormRender, border: false
        }]
    });
    
    var va = [];
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_folder')
        ,description: '<b>[[*isfolder]]</b><br />'+_('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-resource-isfolder'
        ,inputValue: 1
        ,checked: config.record.isfolder || 0
    });
    va.push({
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
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,allowBlank: true
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
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,allowBlank: true
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
        ,checked: config.record.searchable
    });
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: '<b>[[*cacheable]]</b><br />'+_('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-resource-cacheable'
        ,inputValue: 1
        ,checked: config.record.cacheable
    });
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_syncsite')
        ,description: _('resource_syncsite_help')
        ,name: 'syncsite'
        ,id: 'modx-weblink-syncsite'
        ,inputValue: 1
        ,checked: config.record.syncsite || true
    });
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('deleted')
        ,description: '<b>[[*deleted]]</b>'
        ,name: 'deleted'
        ,id: 'modx-resource-deleted'
        ,inputValue: 1
        ,checked: config.record.deleted || false
    });
    va.push({
        xtype: 'modx-combo-content-type'
        ,fieldLabel: _('resource_content_type')
        ,description: '<b>[[*content_type]]</b><br />'+_('resource_content_type_help')
        ,name: 'content_type'
        ,id: 'modx-resource-content-type'
        ,anchor: '70%'
        ,value: config.record.content_type || 1
    });
    va.push({
        xtype: 'modx-combo-content-disposition'
        ,fieldLabel: _('resource_contentdispo')
        ,description: '<b>[[*content_dispo]]</b><br />'+_('resource_contentdispo_help')
        ,name: 'content_dispo'
        ,id: 'modx-resource-content-dispo'
        ,anchor: '70%'
        ,value: config.record.content_dispo || 1
    });
    va.push({
        xtype: 'modx-combo-class-map'
        ,fieldLabel: _('class_key')
        ,description: '<b>[[*class_key]]</b>'
        ,name: 'class_key'
        ,hiddenName: 'class_key'
        ,id: 'modx-resource-class-key'
        ,baseParams: { action: 'getList', parentClass: 'modResource' }
        ,allowBlank: false
        ,value: config.record.class_key || 'modStaticResource'
        ,anchor: '70%'
    });
    va.push({
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_uri_override')
        ,description: _('resource_uri_override_help')
        ,name: 'uri_override'
        ,value: 1
        ,checked: config.record.uri_override ? true : false
        ,id: 'modx-resource-uri-override'

    });
    va.push({
        xtype: 'textfield'
        ,fieldLabel: _('resource_uri')
        ,description: '<b>[[*uri]]</b><br />'+_('resource_uri_help')
        ,name: 'uri'
        ,id: 'modx-resource-uri'
        ,maxLength: 255
        ,anchor: '70%'
        ,value: config.record.uri || ''
        ,hidden: config.record.uri_override ? false : true
    });
    it.push({
        id: 'modx-page-settings'
        ,title: _('page_settings')
        ,layout: 'form'
        ,labelWidth: 200
        ,bodyStyle: 'padding: 15px;'
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
            ,resource: config.resource
            ,class_key: config.record.class_key || 'modStaticResource'
            ,template: config.record.template
        });
    }
    if (config.access_permissions) {
        it.push({
            id: 'modx-resource-access-permissions'
            ,title: _('access_permissions')
            ,bodyStyle: 'padding: 15px;'
            ,autoHeight: true
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
    }    
    Ext.applyIf(config,{
        id: 'modx-panel-resource'
        ,class_key: 'modStaticResource'
        ,items: [{
            html: '<h2>'+_('static_resource_new')+'</h2>'
            ,id: 'modx-resource-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure(it,{id:'modx-resource-tabs' ,forceLayout: true ,deferredRender: false })]
    });
    MODx.panel.Static.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Static,MODx.panel.Resource,{
    defaultClassKey: 'modStaticResource'
    ,classLexiconKey: 'static_resource'
    ,rteElements: false
});
Ext.reg('modx-panel-static',MODx.panel.Static);