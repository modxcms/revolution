/**
 * Loads the Template panel
 * 
 * @class MODx.panel.Template
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-template
 */
MODx.panel.Template = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/template.php'
        ,baseParams: {}
        ,id: 'modx-panel-template'
		,cls: 'container form-with-labels'
        ,class_key: 'modTemplate'
        ,template: ''
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('template_new')+'</h2>'
            ,id: 'modx-template-header'
            ,cls: 'modx-page-header'
            ,border: false
        },MODx.getPageStructure([{
            title: _('template_title')
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,id: 'modx-template-form'
            ,labelWidth: 150
            ,items: [{
                html: '<p>'+_('template_msg')+'</p>'
                ,id: 'modx-template-msg'
				,bodyCssClass: 'panel-desc'
            },{
                layout: 'column'
                ,border: false
                ,defaults: {
                    layout: 'form'
                    ,labelAlign: 'top'
                    ,anchor: '100%'
                    ,border: false
                    ,cls:'main-wrapper'
                    ,labelSeparator: ''
                }
                ,items: [{
                    columnWidth: .6
                    ,items: [{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'modx-template-id'
                        ,value: config.template
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-template-props'
                        ,value: config.record.props || null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('name')+'<span class="required">*</span>'
                        ,description: MODx.expandHelp ? '' : _('template_desc_name')
                        ,name: 'templatename'
                        ,id: 'modx-template-templatename'
                        ,anchor: '100%'
                        ,maxLength: 100
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,value: config.record.templatename
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('modx-template-header').getEl().update('<h2>'+_('template')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden' 
                        ,forId: 'modx-template-templatename'
                        ,html: _('template_desc_name')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'textarea'
                        ,fieldLabel: _('template_desc')
                        ,description: MODx.expandHelp ? '' : _('template_desc_description')
                        ,name: 'description'
                        ,id: 'modx-template-description'
                        ,anchor: '100%'
                        ,maxLength: 255
                        ,value: config.record.description || ''
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: 'modx-template-description'
                        ,html: _('template_desc_description')
                        ,cls: 'desc-under'
                    },{
                        html: MODx.onTempFormRender
                        ,border: false
                    }]
                },{
                    columnWidth: .4
                    ,items: [{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,description: MODx.expandHelp ? '' : _('template_desc_category')
                        ,name: 'category'
                        ,id: 'modx-template-category'
                        ,anchor: '100%'
                        ,value: config.record.category || 0
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden' 
                        ,forId: 'modx-template-category'
                        ,html: _('template_desc_category')
                        ,cls: 'desc-under'
                    },{
                        xtype: 'xcheckbox'
                        ,boxLabel: _('template_lock')
                        ,description: _('template_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-template-locked'
                        ,inputValue: 1
                        ,checked: config.record.locked || false
                    },{
                        xtype: 'checkbox'
                        ,boxLabel: _('clear_cache_on_save')
                        ,description: _('clear_cache_on_save_msg')
                        ,hideLabel: true
                        ,name: 'clearCache'
                        ,id: 'modx-template-clear-cache'
                        ,inputValue: 1
                        ,checked: Ext.isDefined(config.record.clearCache) || true
                    }]
                }]
			},{
				xtype: 'panel'
				,border: false
				,layout: 'form'
				,cls:'main-wrapper'
				,labelAlign: 'top'
				,items: [{
					xtype: 'textarea'
					,fieldLabel: _('template_code')						
					,name: 'content'
					,id: 'modx-template-content'
					,anchor: '100%'
					,height: 400
					,value: config.record.content || ''
				}]
			}]
        },{
            xtype: 'modx-panel-element-properties'
            ,preventRender: true
            ,collapsible: true
            ,elementPanel: 'modx-panel-template'
            ,elementId: config.template
            ,elementType: 'modTemplate'
        },{
            title: _('template_variables')
            ,itemId: 'form-template'
            ,defaults: { autoHeight: true }
			,layout: 'form'
            ,items: [{
                html: '<p>'+_('template_tv_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
               xtype: 'modx-grid-template-tv'
			   ,cls:'main-wrapper'
               ,preventRender: true
               ,anchor: '100%'
               ,template: config.template
               ,listeners: {
                    'rowclick': {fn:this.markDirty,scope:this}
                    ,'afterEdit': {fn:this.markDirty,scope:this}
                    ,'afterRemoveRow': {fn:this.markDirty,scope:this}
               }
            }]
        }],{
            id: 'modx-template-tabs'
        })]
        ,useLoadingMask: true
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Template,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (!this.initialized) { this.getForm().setValues(this.config.record); }
        if (!Ext.isEmpty(this.config.record.templatename)) {
            Ext.getCmp('modx-template-header').getEl().update('<h2>'+_('template')+': '+this.config.record.templatename+'</h2>');
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
        MODx.fireEvent('ready');
        return true;
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-template-tv');
        Ext.apply(o.form.baseParams,{
            tvs: g.encodeModified()
            ,propdata: Ext.getCmp('modx-grid-element-properties').encode()
        });
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
            ,stay: MODx.config.stay
        });
    }
    ,success: function(r) {
        if (MODx.request.id) Ext.getCmp('modx-grid-element-properties').save();
        Ext.getCmp('modx-grid-template-tv').getStore().commitChanges();
        this.getForm().setValues(r.result.object);
        
        var t = Ext.getCmp('modx-element-tree');
        if (t) {
            var c = Ext.getCmp('modx-template-category').getValue();
            var u = c != '' && c != null && c != 0 ? 'n_template_category_'+c : 'n_type_template';
            var node = t.getNodeById('n_template_element_' + Ext.getCmp('modx-template-id').getValue() + '_' + r.result.object.previous_category);
            if (node) node.destroy();
            t.refreshNode(u,true);
        }
    }
    ,changeEditor: function() {
        this.cleanupEditor();
        this.on('success',function(o) {
            var id = o.result.object.id;
            var w = Ext.getCmp('modx-template-which-editor').getValue();
            MODx.request.a = MODx.action['element/template/update'];
            var u = '?'+Ext.urlEncode(MODx.request)+'&which_editor='+w+'&id='+id;
            location.href = u;
        });
        this.submit();
    }    
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-template-content');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-template',MODx.panel.Template);