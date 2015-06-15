/**
 * @class MODx.panel.Context
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-context
 */
MODx.panel.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'context/get'
        }
        ,id: 'modx-panel-context'
		,cls: 'container'
        ,class_key: 'modContext'
        ,plugin: ''
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('context')+': '+config.context+'</h2>'
            ,border: false
            ,id: 'modx-context-name'
            ,cls: 'modx-page-header'
        },MODx.getPageStructure([{
            title: _('general_information')
            ,autoHeight: true
            ,layout: 'form'
            ,defaults: { border: false ,msgTarget: 'side' }
			,items:[{
				xtype: 'panel'
				,border: false
				,cls:'main-wrapper'
				,layout: 'form'
				,items: [{
					xtype: 'statictextfield'
					,fieldLabel: _('key')
					,name: 'key'
					,width: 300
					,maxLength: 100
					,enableKeyEvents: true
					,allowBlank: true
					,value: config.context
					,submitValue: true
				},{
					xtype: 'textfield'
					,fieldLabel: _('name')
					,name: 'name'
					,width: 300
					,maxLength: 255
				},{
					xtype: 'textarea'
					,fieldLabel: _('description')
					,name: 'description'
					,width: 300
					,grow: true
				},{
					html: MODx.onContextFormRender
					,border: false
				}]
			}]
        },{
            title: _('context_settings')
            ,autoHeight: true
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('context_settings_desc')+'</p>'
                ,id: 'modx-context-settings-desc'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-context-settings'
				,cls:'main-wrapper'
                ,title: ''
                ,preventRender: true
                ,context_key: config.context
                ,listeners: {
                    'afteredit': {fn:function() { this.markDirty(); },scope:this}
                }
            }]
        },{
            title: _('access_permissions')
            ,autoHeight: true
            ,items:[{
                xtype: 'modx-grid-access-context'
				,cls:'main-wrapper'
                ,title: ''
                ,preventRender: true
                ,context_key: config.context
                ,listeners: {
                    'afteredit': {fn:function() { this.markDirty(); },scope:this}
                }
            }]
        }],{
            id: 'modx-context-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Context,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized || (this.config.context === '' || this.config.context === 0)) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'context/get'
                ,key: this.config.context
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    var el = Ext.getCmp('modx-context-name');
                    if (el) { el.getEl().update('<h2>'+_('context')+': '+r.object.key+'</h2>'); }
                    this.fireEvent('ready');
                    MODx.fireEvent('ready');
                    this.initialized = true;
            	},scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var r = {};

        var g = Ext.getCmp('modx-grid-context-settings');
        if (g) { r.settings = g.encodeModified(); }

        Ext.apply(o.form.baseParams,r);
    }
    ,success: function(o) {
        var g = Ext.getCmp('modx-grid-context-settings');
        if (g) { g.getStore().commitChanges(); }

        var t = parent.Ext.getCmp('modx-resource-tree');
        if (t) { t.refresh(); }
    }
});
Ext.reg('modx-panel-context',MODx.panel.Context);
