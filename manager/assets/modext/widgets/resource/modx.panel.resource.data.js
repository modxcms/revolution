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
            action: 'resource/data'
        }
        ,id: 'modx-panel-resource-data'
        ,class_key: 'modResource'
        ,cls: 'container form-with-labels'
        ,resource: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2></h2>'
            ,id: 'modx-resource-header'
            ,cls: 'modx-page-header'
        },MODx.getPageStructure([{
            title: _('general')
            ,id: 'modx-rdata-tab-general'
            ,layout: 'form'
            ,autoHeight: true
            ,bodyCssClass: 'main-wrapper'
            ,labelWidth: 150
            ,defaults: df
            ,items: [{
                name: 'pagetitle'
                ,fieldLabel: _('resource_pagetitle')
                ,description: _('resource_pagetitle_help')
                ,xtype: 'statictextfield'
            },{
                name: 'template_name'
                ,fieldLabel: _('resource_template')
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
                name: 'class_key'
                ,fieldLabel: _('class_key')
                ,description: _('resource_class_key_help')
                ,xtype: 'statictextfield'
            },{
                name: 'alias'
                ,fieldLabel: _('resource_alias')
                ,description: _('resource_alias_help')
                ,xtype: 'statictextfield'
            },{
                name: 'context_key'
                ,fieldLabel: _('context')
                ,xtype: 'statictextfield'
            },{
                name: 'status'
                ,fieldLabel: _('resource_status')
                ,description: _('resource_status_help')
            },{
                name: 'deleted'
                ,fieldLabel: _('deleted')
                ,xtype: 'staticboolean'
            },{
                name: 'pub_date'
                ,fieldLabel: _('resource_publishdate')
                ,description: _('resource_publishdate_help')
            },{
                name: 'unpub_date'
                ,fieldLabel: _('resource_unpublishdate')
                ,description: _('resource_unpublishdate_help')
            },{
                name: 'cacheable'
                ,fieldLabel: _('resource_cacheable')
                ,description: _('resource_cacheable_help')
                ,xtype: 'staticboolean'
            },{
                name: 'searchable'
                ,fieldLabel: _('resource_searchable')
                ,description: _('resource_searchable_help')
                ,xtype: 'staticboolean'
            },{
                name: 'hidemenu'
                ,fieldLabel: _('resource_hide_from_menus')
                ,description: _('resource_hide_from_menus_help')
                ,xtype: 'staticboolean'
            },{
                name: 'menutitle'
                ,fieldLabel: _('resource_menutitle')
                ,description: _('resource_menutitle_help')
            },{
                name: 'menuindex'
                ,fieldLabel: _('resource_menuindex')
                ,description: _('resource_menuindex_help')
            },{
                name: 'richtext'
                ,fieldLabel: _('resource_richtext')
                ,description: _('resource_richtext_help')
                ,xtype: 'staticboolean'
            },{
                name: 'isfolder'
                ,fieldLabel: _('resource_folder')
                ,description: _('resource_folder_help')
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
            ,items: [{
                name: 'createdon_adjusted'
                ,fieldLabel: _('resource_createdon')
            },{
                name: 'createdon_by'
                ,fieldLabel: _('resource_createdby')
            },{
                name: 'editedon_adjusted'
                ,fieldLabel: _('resource_editedon')
            },{
                name: 'editedon_by'
                ,fieldLabel: _('resource_editedby')
            },{
                name: 'publishedon_adjusted'
                ,fieldLabel: _('resource_publishedon')
            },{
                name: 'publishedon_by'
                ,fieldLabel: _('resource_publishedby')
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
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'resource/data'
                ,id: this.config.resource
                ,class_key: this.config.class_key
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.pub_date == '0') { r.object.pub_date = ''; }
                    if (r.object.unpub_date == '0') { r.object.unpub_date = ''; }
                    Ext.get('modx-resource-header').update('<h2>'+r.object.pagetitle+'</h2>');
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready');
            	},scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-resource-data',MODx.panel.ResourceData);
