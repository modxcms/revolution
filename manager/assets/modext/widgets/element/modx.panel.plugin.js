/**
 * 
 * @class MODx.panel.Plugin
 * @extends MODx.FormPanel
 * @param {Object} config An object of config properties
 * @xtype panel-plugin
 */
MODx.panel.Plugin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/plugin.php'
        ,baseParams: {}
        ,id: 'modx-panel-plugin'
        ,class_key: 'modPlugin'
        ,plugin: ''
        ,bodyStyle: ''
        ,allowDrop: false
        ,items: [{
            html: '<h2>'+_('plugin_new')+'</h2>'
            ,id: 'modx-plugin-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure([{
            title: _('plugin_title')
            ,bodyStyle: 'padding: 15px;'
            ,layout: 'form'
            ,id: 'modx-plugin-form'
            ,labelWidth: 150
            ,defaults: { border: false ,msgTarget: 'side' }
            ,items: [{
                html: '<p>'+_('plugin_msg')+'</p>'
                ,id: 'modx-plugin-msg'
            },{
                xtype: 'hidden'
                ,name: 'id'
                ,id: 'modx-plugin-id'
                ,value: config.record.id || 0
            },{
                xtype: 'hidden'
                ,name: 'props'
                ,id: 'modx-plugin-props'
                ,value: config.record.props || null
            },{
                xtype: 'textfield'
                ,fieldLabel: _('plugin_name')
                ,name: 'name'
                ,id: 'modx-plugin-name'
                ,width: 300
                ,maxLength: 255
                ,enableKeyEvents: true
                ,allowBlank: false
                ,value: config.record.name
                ,listeners: {
                    'keyup': {scope:this,fn:function(f,e) {
                        Ext.getCmp('modx-plugin-header').getEl().update('<h2>'+_('plugin')+': '+f.getValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'textfield'
                ,fieldLabel: _('plugin_desc')
                ,name: 'description'
                ,id: 'modx-plugin-description'
                ,width: 300
                ,maxLength: 255
                ,value: config.record.description
            },{
                xtype: 'modx-combo-category'
                ,fieldLabel: _('category')
                ,name: 'category'
                ,id: 'modx-plugin-category'
                ,width: 250
                ,value: config.record.category || 0
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('plugin_disabled')
                ,name: 'disabled'
                ,id: 'modx-plugin-disabled'
                ,inputValue: 1
                ,checked: config.record.disabled || 0
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('plugin_lock')
                ,description: _('plugin_lock_msg')
                ,name: 'locked'
                ,id: 'modx-plugin-locked'
                ,inputValue: 1
                ,checked: config.record.locked || 0
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('clear_cache_on_save')
                ,description: _('clear_cache_on_save_msg')
                ,name: 'clearCache'
                ,id: 'modx-plugin-clear-cache'
                ,inputValue: 1
                ,checked: config.record.clearCache || true
            },{
                html: MODx.onPluginFormRender
                ,border: false
            },{
                html: '<br />'+_('plugin_code')
            },{
                xtype: 'textarea'
                ,hideLabel: true
                ,name: 'plugincode'
                ,id: 'modx-plugin-plugincode'
                ,width: '95%'
                ,height: 400
                ,value: config.record.plugincode || "<?php\n"
                
            }]
        },{
            title: _('system_events')
            ,bodyStyle: 'padding: 15px;'
            ,id: 'modx-plugin-sysevents'
            ,items: [{
                html: '<p>'+_('plugin_event_msg')+'</p>'
                ,id: 'modx-plugin-sysevents-msg'
                ,border: false
            },{
                xtype: 'modx-grid-plugin-event'
                ,preventRender: true
                ,plugin: config.plugin
                ,listeners: {
                    'updateEvent': {fn:this.markDirty,scope:this}
                    ,'rowclick': {fn:this.markDirty,scope:this}
                }
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,elementPanel: 'modx-panel-plugin'
            ,elementId: config.plugin
            ,elementType: 'modPlugin'
        }],{
            id: 'modx-plugin-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Plugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Plugin,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!Ext.isEmpty(this.config.record.name)) {
            Ext.getCmp('modx-plugin-header').getEl().update('<h2>'+_('plugin')+': '+this.config.record.name+'</h2>');
        }
        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }
        this.fireEvent('ready',this.config.record);
        if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
        this.clearDirty();
        this.initialized = true;
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-plugin-event');
        Ext.apply(o.form.baseParams,{
            events: g.encodeModified()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(o) {
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        Ext.getCmp('modx-grid-plugin-event').getStore().commitChanges();
        this.getForm().setValues(o.result.object);
        
        var t = Ext.getCmp('modx-element-tree');
        var c = Ext.getCmp('modx-plugin-category').getValue();
        var u = c != '' && c != null ? 'n_plugin_category_'+c : 'n_type_plugin'; 
        t.refreshNode(u,true);
    }    
    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-plugin-which-editor').getValue();
            MODx.request.a = MODx.action['element/plugin/update'];
            var u = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
            location.href = u;
        });
        this.submit();
    }    
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-plugin-plugincode');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-plugin',MODx.panel.Plugin);