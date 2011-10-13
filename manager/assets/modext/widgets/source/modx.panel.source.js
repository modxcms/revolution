MODx.panel.Source = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-source'
        ,url: MODx.config.connectors_url+'source/index.php'
        ,baseParams: {
            action: 'update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: '<h2>'+_('source')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-source-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                bodyStyle: 'padding: 15px;'
                ,autoHeight: true
                ,border: true
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
                ,bodyStyle: 'padding: 15px;'
                ,defaults: { border: false ,msgTarget: 'side' }
                ,layout: 'form'
                ,id: 'modx-dashboard-form'
                ,labelWidth: 150
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
                    ,allowBlank: false
                    ,enableKeyEvents: true
                    ,anchor: '97%'
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('modx-source-header').getEl().update('<h2>'+_('source')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    name: 'description'
                    ,id: 'modx-source-description'
                    ,xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,anchor: '97%'
                    ,grow: true
                },{
                    name: 'class_key'
                    ,hiddenName: 'class_key'
                    ,xtype: 'modx-combo-source-type'
                    ,fieldLabel: _('source_type')
                    ,width: 400

                },{
                    html: '<hr />'
                    ,border: false
                },{
                    html: '<p>'+_('source_properties.intro_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-source-properties'
                    ,preventRender: true
                    ,source: config.record.id
                    ,autoHeight: true
                    ,width: '97%'
                    ,listeners: {
                        'afterRemoveRow': {fn:this.markDirty,scope:this}
                    }
                }]
            },{
                title: _('access')
                ,hideMode: 'offsets'
                ,items: [{
                    html: '<p>'+_('source.access.intro_msg')+'</p>'
                    ,border: false
                },{
                    xtype: 'modx-grid-source-access'
                    ,preventRender: true
                    ,source: config.record.id
                    ,autoHeight: true
                    ,width: '97%'
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
        Ext.get('modx-source-header').update('<h2>'+_('source')+': '+this.config.record.name+'</h2>');

        if (!Ext.isEmpty(this.config.record.properties)) {
            var d = this.config.record.properties;
            var g = Ext.getCmp('modx-grid-source-properties');
            if (g) {
                g.defaultProperties = d;
                g.getStore().loadData(d);
            }
        }
        if (!Ext.isEmpty(this.config.record.access)) {
            var d = this.config.record.access;
            var g = Ext.getCmp('modx-grid-source-access');
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
            location.href = '?a='+MODx.action['source/update']+'&id='+o.result.object.id;
        } else {
            Ext.getCmp('modx-btn-save').setDisabled(false);
            var wg = Ext.getCmp('modx-grid-source-properties');
            if (wg) { wg.getStore().commitChanges(); }
            var ag = Ext.getCmp('modx-grid-source-access');
            if (ag) { ag.getStore().commitChanges(); }
        }
    }
});
Ext.reg('modx-panel-source',MODx.panel.Source);

