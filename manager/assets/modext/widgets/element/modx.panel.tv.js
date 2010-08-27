/**
 * Loads the TV panel
 * 
 * @class MODx.panel.TV
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-tv
 */
MODx.panel.TV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/tv.php'
        ,baseParams: {}
        ,id: 'modx-panel-tv'
        ,class_key: 'modTemplateVar'
        ,tv: ''
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('tv_new')+'</h2>'
            ,id: 'modx-tv-header'
            ,itemId: 'header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure([{
            title: _('general_information')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,bodyStyle: 'padding: 15px;'
            ,layout: 'form'
            ,id: 'modx-tv-form'
            ,itemId: 'form-tv'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('tv_msg')+'</p>'
                ,id: 'modx-tv-msg'
            },{
                xtype: 'hidden'
                ,name: 'id'
                ,id: 'modx-tv-id'
                ,value: config.record.id || MODx.request.id
            },{
                xtype: 'hidden'
                ,name: 'props'
                ,id: 'modx-tv-props'
                ,value: config.record.props || null
            },{
                xtype: 'textfield'
                ,fieldLabel: _('tv_name')
                ,name: 'name'
                ,id: 'modx-tv-name'
                ,width: 300
                ,maxLength: 100
                ,enableKeyEvents: true
                ,allowBlank: false
                ,value: config.record.name
                ,listeners: {
                    'keyup': {scope:this,fn:function(f,e) {
                        Ext.getCmp('modx-tv-header').getEl().update('<h2>'+_('tv')+': '+f.getValue()+'</h2>');
                    }}
                }
            },{
                xtype: 'textfield'
                ,fieldLabel: _('tv_caption')
                ,name: 'caption'
                ,id: 'modx-tv-caption'
                ,width: 300
                ,value: config.record.caption
            },{
                xtype: 'textfield'
                ,fieldLabel: _('description')
                ,name: 'description'
                ,id: 'modx-tv-description'
                ,width: 300
                ,maxLength: 255
                ,value: config.record.description || ''
            },{
                xtype: 'modx-combo-category'
                ,fieldLabel: _('category')
                ,name: 'category'
                ,id: 'modx-tv-category'
                ,width: 250
                ,value: config.record.category || 0
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('tv_lock')
                ,description: _('tv_lock_msg')
                ,name: 'locked'
                ,id: 'modx-tv-locked'
                ,inputValue: 1
                ,checked: config.record.locked || false
            },{
                xtype: 'numberfield'
                ,fieldLabel: _('tv_rank')
                ,name: 'rank'
                ,id: 'modx-tv-rank'
                ,width: 50
                ,maxLength: 4
                ,allowNegative: false
                ,allowBlank: false
                ,value: config.record.rank || 0
            },{
                xtype: 'checkbox'
                ,fieldLabel: _('clear_cache_on_save')
                ,description: _('clear_cache_on_save_msg')
                ,name: 'clearCache'
                ,id: 'modx-tv-clear-cache'
                ,inputValue: 1
                ,checked: config.record.clearCache || true
            },{
                html: onTVFormRender
                ,border: false
            },MODx.PanelSpacer,{
                xtype: 'fieldset'
                ,itemId: 'fs-rendering'
                ,title: _('rendering_options')
                ,autoHeight: true
                ,border: true
                ,collapsible: true
                ,defaults: { autoHeight: true }
                ,items: [{
                    xtype: 'modx-combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,id: 'modx-tv-type'
                    ,itemid: 'fld-type'
                    ,value: config.record.type || 'text'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_elements')
                    ,name: 'els'
                    ,id: 'modx-tv-elements'
                    ,itemId: 'fld-els'
                    ,anchor: '90%'
                    ,value: config.record.elements || ''
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default')
                    ,name: 'default_text'
                    ,id: 'modx-tv-default-text'
                    ,itemId: 'fld-default_text'
                    ,anchor: '90%'
                    ,height: 200
                    ,value: config.record.default_text || ''
                },{
                    xtype: 'modx-combo-tv-widget'
                    ,fieldLabel: _('tv_output_type')
                    ,name: 'display'
                    ,hiddenName: 'display'
                    ,id: 'modx-tv-display'
                    ,itemId: 'fld-display'
                    ,value: config.record.display || 'default'
                    ,listeners: {
                        'select': {fn:this.showParameters,scope:this}
                    }
                },{
                    id: 'modx-widget-props'
                    ,autoHeight: true
                }]
            }]
        },{
            xtype: 'modx-panel-element-properties'
            ,itemId: 'panel-properties'
            ,elementPanel: 'modx-panel-tv'
            ,elementId: config.tv
            ,elementType: 'modTemplateVar'
        },{ 
            title: _('tv_tmpl_access')
            ,itemId: 'form-template'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { autoHeight: true }
            ,items: [{
                html: '<p>'+_('tv_tmpl_access_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-tv-template'
                ,itemId: 'grid-template'
                ,tv: config.tv
                ,preventRender: true
                ,width: '100%'
                ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afteredit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
                }
            }]
        },{
            title: _('access_permissions')
            ,id: 'modx-tv-access-form'
            ,itemId: 'form-access'
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { autoHeight: true }
            ,items: [{
                html: '<p>'+_('tv_access_msg')+'</p>'
                ,id: 'modx-tv-access-msg'
                ,border: false
            },{
                xtype: 'modx-grid-tv-security'
                ,itemId: 'grid-access'
                ,tv: config.tv
                ,preventRender: true
                ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afteredit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
                }
            }]
        }],{
            id: 'modx-tv-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.TV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.TV,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!Ext.isEmpty(this.config.record.name)) {
            Ext.getCmp('modx-tv-header').getEl().update('<h2>'+_('tv')+': '+this.config.record.name+'</h2>');
        }
        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-element-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }

        var dis = this.getComponent('tabs').getComponent('form-tv').getComponent('fs-rendering').getComponent('fld-display');
        this.showParameters(dis);

        this.fireEvent('ready',this.config.record);
        if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
        this.clearDirty();
        this.initialized = true;
        return true;
    }
    
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-tv-template');
        var rg = Ext.getCmp('modx-grid-tv-security');
        Ext.apply(o.form.baseParams,{
            templates: g.encodeModified()
            ,resource_groups: rg.encodeModified()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(r) {
        Ext.getCmp('modx-grid-tv-template').getStore().commitChanges();
        Ext.getCmp('modx-grid-tv-security').getStore().commitChanges();
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        this.getForm().setValues(r.result.object);
        
        var t = Ext.getCmp('modx-element-tree');
        var c = Ext.getCmp('modx-tv-category').getValue();
        var u = c != '' && c != null ? 'n_tv_category_'+c : 'n_type_tv'; 
        t.refreshNode(u,true);
    }
    
    ,showParameters: function(cb,rc,i) {
        var pu = Ext.get('modx-widget-props').getUpdater();
        pu.loadScripts = true;
        
        try {
            pu.update({
                url: MODx.config.connectors_url+'element/tv/renders.php'
                ,method: 'GET'
                ,params: {
                   'action': 'getProperties'
                   ,'context': 'mgr'
                   ,'tv': this.config.tv
                   ,'type': cb.getValue() || 'default'
                }
                ,scripts: true
            });
        } catch(e) { console.log(e); }
        
    }
    ,changeEditor: function() {
        this.cleanupEditor();
        this.submit();
    }
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-tv-default-text');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-tv',MODx.panel.TV); 