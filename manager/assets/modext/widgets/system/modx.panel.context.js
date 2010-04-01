/**
 * @class MODx.panel.Context
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype modx-panel-context
 */
MODx.panel.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'context/index.php'
        ,baseParams: {}
        ,id: 'modx-panel-context'
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
            ,bodyStyle: 'padding: 15px'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,items: [{
                xtype: 'statictextfield'
                ,fieldLabel: _('key')
                ,name: 'key'
                ,width: 300
                ,maxLength: 255
                ,enableKeyEvents: true
                ,allowBlank: false
                ,value: config.context
                ,submitValue: true
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
        },{
            title: _('context_settings')
            ,bodyStyle: 'padding: 1.5em;'
            ,autoHeight: true
            ,items: [{
                html: '<p>'+_('context_settings_desc')+'</p>'
                ,id: 'modx-context-settings-desc'
                ,border: false
            },{
                xtype: 'modx-grid-context-settings'
                ,title: ''
                ,preventRender: true
                ,context_key: config.context
            }]
        },{
            title: _('access_permissions')
            ,bodyStyle: 'padding: 1.5em'
            ,autoHeight: true
            ,items:[{
                xtype: 'modx-grid-access-context'
                ,title: ''
                ,preventRender: true
                ,context_key: config.context
            }]
        }])]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
        }
    });
    MODx.panel.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Context,MODx.FormPanel,{
    setup: function() {
        if (this.config.context === '' || this.config.context === 0) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,key: this.config.context
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    var el = Ext.getCmp('modx-context-name');
                    if (el) { el.getEl().update('<h2>'+_('context')+': '+r.object.key+'</h2>'); }
                    this.fireEvent('ready');
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
        if (t) { t.refreshNode(this.config.context+'_0',true); }
    }
});
Ext.reg('modx-panel-context',MODx.panel.Context);