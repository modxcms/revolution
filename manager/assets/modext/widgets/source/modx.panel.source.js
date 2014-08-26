MODx.panel.Source = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-source'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'source/update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
		,cls: 'container form-with-labels'
        ,items: [{
             html: '<h2>'+_('source')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-source-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: true
				,bodyCssClass: 'tab-panel-wrapper'
            }
            ,id: 'modx-source-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-source-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                title: _('general_information')
				,defaults: { border: false, msgTarget: 'side' }
                ,layout: 'form'
                ,id: 'modx-dashboard-form'
                ,labelWidth: 150
                ,items: [{
					xtype: 'panel'
					,border: false
					,cls: 'main-wrapper'
					,layout: 'form'
					,labelAlign: 'top'
					,items: [{
					    layout: 'column'
					    ,border: false
                        ,defaults: {
                            layout: 'form'
                            ,labelAlign: 'top'
                            ,anchor: '100%'
                            ,border: false
                        }
					    ,items: [{
                            columnWidth: .65
                            ,cls: 'main-content'
                            ,items: [{
                                xtype: 'hidden'
                                ,name: 'id'
                                ,id: 'modx-source-id'
                                ,value: config.record.id
                            },{
                                name: 'name'
                                ,id: 'modx-source-name'
                                ,xtype: 'textfield'
                                ,fieldLabel: _('name')
                                ,description: MODx.expandHelp ? '' : _('source_name_desc')
                                ,allowBlank: false
                                ,enableKeyEvents: true
                                ,anchor: '100%'
                                ,listeners: {
                                    'keyup': {scope:this,fn:function(f,e) {
                                        Ext.getCmp('modx-source-header').getEl().update('<h2>'+_('source')+': '+f.getValue()+'</h2>');
                                    }}
                                }
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-source-name'
                                ,html: _('source_name_desc')
                                ,cls: 'desc-under'
                            },{
                                name: 'description'
                                ,id: 'modx-source-description'
                                ,xtype: 'textarea'
                                ,fieldLabel: _('description')
                                ,description: MODx.expandHelp ? '' : _('source_description_desc')
                                ,anchor: '100%'
                                ,grow: true
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-source-description'
                                ,html: _('source_description_desc')
                                ,cls: 'desc-under'
                            }]
                        },{
                            columnWidth: .35
                            ,cls: 'main-content'
                            ,items: [{
                                name: 'class_key'
                                ,hiddenName: 'class_key'
                                ,id: 'modx-source-type'
                                ,xtype: 'modx-combo-source-type'
                                ,fieldLabel: _('source_type')
                                ,description: MODx.expandHelp ? '' : _('source_type_desc')
                                ,anchor: '100%'
                            },{
                                xtype: MODx.expandHelp ? 'label' : 'hidden'
                                ,forId: 'modx-source-type'
                                ,html: _('source_type_desc')
                                ,cls: 'desc-under'
                            }]

                        }]
                    }]
                },{
                    html: '<p>'+_('source_properties.intro_msg')+'</p>'
					,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-source-properties'
                    ,preventRender: true
                    ,source: config.record.id
                    ,defaultProperties: config.defaultProperties
                    ,autoHeight: true
                    ,cls: 'main-wrapper'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                    }
                }]
            },{
                title: _('access')
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('source.access.intro_msg')+'</p>'
					,bodyCssClass: 'panel-desc'
                    ,border: false
                },{
                    xtype: 'modx-grid-source-access'
                    ,preventRender: true
                    ,source: config.record.id
                    ,autoHeight: true
                    ,cls: 'main-wrapper'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                        ,'updateRole': {fn:this.markDirty,scope:this}
                        ,'addMember': {fn:this.markDirty,scope:this}
                    }
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Source.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Source,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.initialized) { return false; }
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
		/* The component rendering is deferred since we are not using renderTo */
        Ext.getCmp('modx-source-header').html = '<h2>'+_('source')+': '+this.config.record.name+'</h2>';

        var g,d;
        if (!Ext.isEmpty(this.config.record.properties)) {
            g = Ext.getCmp('modx-grid-source-properties');
            if (g) {
                g.defaultProperties = this.config.defaultProperties;
                g.getStore().loadData(this.config.record.properties);
            }
        }
        if (!Ext.isEmpty(this.config.record.access)) {
            d = this.config.record.access;
            g = Ext.getCmp('modx-grid-source-access');
            if (g) {
                d = Ext.decode(d);
                if (!Ext.isEmpty(d)) {
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                }
            }
        }

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
        this.initialized = true;
    }
    ,beforeSubmit: function(o) {
        var bp = {};
        var sp = Ext.getCmp('modx-grid-source-properties');
        if (sp) {
            bp.properties = sp.encode();
        }
        var ap = Ext.getCmp('modx-grid-source-access');
        if (ap) {
            bp.access = ap.encode();
        }
        Ext.apply(o.form.baseParams,bp);
    }
    ,success: function(o) {
        if (Ext.isEmpty(this.config.record) || Ext.isEmpty(this.config.record.id)) {
            MODx.loadPage('source/update', 'id='+o.result.object.id);
        } else {
            Ext.getCmp('modx-abtn-save').setDisabled(false);
            var wg = Ext.getCmp('modx-grid-source-properties');
            if (wg) { wg.getStore().commitChanges(); }
            var ag = Ext.getCmp('modx-grid-source-access');
            if (ag) { ag.getStore().commitChanges(); }
        }
    }
});
Ext.reg('modx-panel-source',MODx.panel.Source);

