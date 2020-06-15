MODx.panel.ResourceData = function(config) {
    config = config || {};
    var df = {
        border: false
        ,msgTarget: 'side'
        ,width: 300
    };
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Resource/Data'
        }
        ,id: 'modx-panel-resource-data'
        ,class_key: 'MODX\\Revolution\\modResource'
        ,cls: 'container form-with-labels'
        ,resource: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [this.getPageHeader(config) ,MODx.getPageStructure([{
            title: _('general')
            ,id: 'modx-rdata-tab-general'
            ,layout: 'form'
            ,autoHeight: true
            ,bodyCssClass: 'main-wrapper'
            ,labelWidth: 150
            ,defaults: df
            ,items: [{
                name: 'context_key'
                ,fieldLabel: _('context')
                ,xtype: 'statictextfield'
            },{
                name: 'alias'
                ,fieldLabel: _('resource_alias')
                ,description: _('resource_alias_help')
                ,xtype: 'statictextfield'
            },{
                name: 'template_name'
                ,fieldLabel: _('resource_template')
                ,xtype: 'statictextfield'
            },{
                name: 'pagetitle'
                ,fieldLabel: _('resource_pagetitle')
                ,description: _('resource_pagetitle_help')
                ,xtype: 'statictextfield'
            },{
                name: 'longtitle'
                ,fieldLabel: _('resource_longtitle')
                ,description: _('resource_longtitle_help')
                ,xtype: 'statictextfield'
                ,value: _('notset')
                ,width: 500
            },{
                name: 'description'
                ,fieldLabel: _('resource_description')
                ,description: _('resource_description_help')
                ,xtype: 'statictextfield'
                ,width: 500
            },{
                name: 'introtext'
                ,fieldLabel: _('resource_summary')
                ,description: _('resource_summary_help')
                ,xtype: 'statictextfield'
            },{
                name: 'content'
                ,fieldLabel: _('resource_content')
                ,xtype: 'statictextfield'
            },{
                name: 'published'
                ,fieldLabel: _('resource_published')
                ,description: _('resource_published_help')
                ,xtype: 'staticboolean'
            },{
                name: 'deleted'
                ,fieldLabel: _('deleted')
                ,xtype: 'staticboolean'
            },{
                name: 'publishedon'
                ,fieldLabel: _('resource_publishedon')
                ,description: _('resource_publishedon_help')
                ,xtype: 'statictextfield'
            },{
                name: 'pub_date'
                ,fieldLabel: _('resource_publishdate')
                ,description: _('resource_publishdate_help')
                ,xtype: 'statictextfield'
            },{
                name: 'unpub_date'
                ,fieldLabel: _('resource_unpublishdate')
                ,description: _('resource_unpublishdate_help')
                ,xtype: 'statictextfield'
            },{
                name: 'hidemenu'
                ,fieldLabel: _('resource_hide_from_menus')
                ,description: _('resource_hide_from_menus_help')
                ,xtype: 'staticboolean'
            },{
                name: 'menutitle'
                ,fieldLabel: _('resource_menutitle')
                ,description: _('resource_menutitle_help')
                ,xtype: 'statictextfield'
            },{
                name: 'menuindex'
                ,fieldLabel: _('resource_menuindex')
                ,description: _('resource_menuindex_help')
                ,xtype: 'statictextfield'
            },{
                name: 'link_attributes'
                ,fieldLabel: _('resource_link_attributes')
                ,description: _('resource_link_attributes_help')
                ,xtype: 'statictextfield'
            },{
                name: 'class_key'
                ,fieldLabel: _('class_key')
                ,description: _('resource_class_key_help')
                ,xtype: 'statictextfield'
            },{
                name: 'content_type'
                ,fieldLabel: _('resource_content_type')
                ,description: _('resource_content_type_help')
                ,xtype: 'statictextfield'
            },{
                name: 'isfolder'
                ,fieldLabel: _('resource_folder')
                ,description: _('resource_folder_help')
                ,xtype: 'staticboolean'
            },{
                name: 'show_in_tree'
                ,fieldLabel: _('resource_show_in_tree')
                ,description: _('resource_show_in_tree_help')
                ,xtype: 'staticboolean'
            },{
                name: 'hide_children_in_tree'
                ,fieldLabel: _('resource_hide_children_in_tree')
                ,description: _('resource_hide_children_in_tree_help')
                ,xtype: 'staticboolean'
            },{
                name: 'alias_visible'
                ,fieldLabel: _('resource_alias_visible')
                ,description: _('resource_alias_visible_help')
                ,xtype: 'staticboolean'
            },{
                name: 'uri_override'
                ,fieldLabel: _('resource_uri_override')
                ,description: _('resource_uri_override_help')
                ,xtype: 'staticboolean'
            },{
                name: 'uri'
                ,fieldLabel: _('resource_uri')
                ,description: _('resource_uri_help')
                ,xtype: 'statictextfield'
            },{
                name: 'parent'
                ,fieldLabel: _('resource_parent')
                ,description: _('resource_parent_help')
                ,xtype: 'statictextfield'
            },{
                name: 'content_dispo'
                ,fieldLabel: _('resource_contentdispo')
                ,description: _('resource_contentdispo_help')
                ,xtype: 'statictextfield'
            },{
                name: 'richtext'
                ,fieldLabel: _('resource_richtext')
                ,description: _('resource_richtext_help')
                ,xtype: 'staticboolean'
            },{
                name: 'searchable'
                ,fieldLabel: _('resource_searchable')
                ,description: _('resource_searchable_help')
                ,xtype: 'staticboolean'
            },{
                name: 'cacheable'
                ,fieldLabel: _('resource_cacheable')
                ,description: _('resource_cacheable_help')
                ,xtype: 'staticboolean'
            }]
        },{
            title: _('changes')
            ,id: 'modx-rdata-tab-changes'
            ,defaults: df
            ,layout: 'form'
            ,autoHeight: true
            ,bodyCssClass: 'main-wrapper'
            ,defaultType: 'statictextfield'
            ,anchor: '100%'
            ,items: [{
                name: 'createdon_adjusted'
                ,fieldLabel: _('resource_createdon')
            },{
                name: 'createdon_by'
                ,fieldLabel: _('resource_createdby')
            },{
                name: 'publishedon_adjusted'
                ,fieldLabel: _('resource_publishedon')
            },{
                name: 'publishedon_by'
                ,fieldLabel: _('resource_publishedby')
            },{
                name: 'editedon_adjusted'
                ,fieldLabel: _('resource_editedon')
            },{
                name: 'editedon_by'
                ,fieldLabel: _('resource_editedby')
            },{
                xtype: 'modx-grid-manager-log'
                ,anchor: '100%'
                ,preventRender: true
                ,formpanel: 'modx-panel-manager-log'
                ,baseParams: {
                    action: 'System/Log/GetList'
                    ,item: MODx.request.id
                    ,classKey: 'MODX\\Revolution\\modResource'
                }
                ,tbar: []
            }]
        },{
            title: _('cache_output')
            ,bodyCssClass: 'main-wrapper'
            ,autoHeight: true
            ,id: 'modx-rdata-tab-source'
            ,items: [{
                name: 'buffer'
                ,id: 'modx-rdata-buffer'
                ,xtype: 'textarea'
                ,hideLabel: true
                ,readOnly: true
                ,width: '90%'
                ,grow: true
            }]
        }],{
            deferredRender: false
            ,hideMode: 'offsets'
        })]
        ,listeners: {
            'setup':{fn:this.setup,scope:this}
        }
    });
    MODx.panel.ResourceData.superclass.constructor.call(this,config);

    // prevent backspace key from going to the previous page in browser history
    Ext.EventManager.on(window, 'keydown', function(e, t) {
        if (e.getKey() == e.BACKSPACE && t.readOnly) {
            e.stopEvent();
        }
    });
};
Ext.extend(MODx.panel.ResourceData,MODx.FormPanel,{
    setup: function() {

        if (this.config.resource === '' || this.config.resource === 0) {
            this.fireEvent('ready');
        	return false;
        }
        var g = Ext.getCmp('modx-grid-manager-log');
        g.getStore().baseParams.item = this.config.resource;
        g.getStore().baseParams.classKey = 'modResource,'+this.config.class_key;
        g.getBottomToolbar().changePage(1);
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'Resource/Data'
                ,id: this.config.resource
                ,class_key: this.config.class_key
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    Ext.get('modx-resource-header').update(r.object.pagetitle);
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    },
    getPageHeader: function(config) {
        config = config || {record:{}};
        var header = {
            html: config.record.pagetitle || ''
            ,id: 'modx-resource-header'
            ,xtype: 'modx-header'
        };

        // Add breadcrumbs with parents
        if (config.record['parents'] && config.record['parents'].length) {
            var parents = config.record['parents'];
            var trail = [];
            for (var i = 0; i < parents.length; i++) {
                if (parents[i].id) {
                    if (parents[i].parent && i == 1) {
                        trail.push({
                            text: parents[i].parent && i == 1 ? '...' : parents[i].pagetitle
                            ,href: false
                        });
                    }
                    trail.push({
                        text: parents[i].pagetitle
                        ,href: MODx.config.manager_url + '?a=Resource/Data&id=' + parents[i].id
                        ,cls: function(data) {
                            var cls = [];
                            if (!data.published) {
                                cls.push('not_published');
                            }
                            if (data.hidemenu) {
                                cls.push('menu_hidden');
                            }
                            return cls.join(' ');
                        }(parents[i])
                    });
                } else {
                    trail.push({
                        text: '<i class="icon icon-globe"></i> ' + (parents[i].name || parents[i].key)
                        //,href: MODx.config.manager_url + '?a=Context/Update&key=' + parents[i].key
                        ,href: false
                    });
                }
            }

            return MODx.util.getHeaderBreadCrumbs(header, trail);
        } else {
            return header;
        }
    }
});
Ext.reg('modx-panel-resource-data',MODx.panel.ResourceData);
