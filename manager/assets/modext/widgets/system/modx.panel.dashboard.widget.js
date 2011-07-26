MODx.panel.DashboardWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-dashboard-widget'
        ,url: MODx.config.connectors_url+'system/dashboard/widget.php'
        ,baseParams: {
            action: 'update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: '<h2>'+_('widget')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-dashboard-widget-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                bodyStyle: 'padding: 15px;'
                ,autoHeight: true
                ,border: true
            }
            ,id: 'modx-dashboard-widget-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-dashboard-widget-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            ,items: [{
                title: _('general_information')
                ,bodyStyle: 'padding: 15px;'
                ,defaults: { border: false ,msgTarget: 'side' }
                ,layout: 'form'
                ,id: 'modx-dashboard-widget-form'
                ,labelWidth: 150
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,fieldLabel: _('id')
                    ,id: 'modx-dashboard-widget-id'
                    ,value: config.record.id
                },{
                    name: 'name'
                    ,id: 'modx-dashboard-widget-name'
                    ,xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,description: _('widget_name_desc')
                    ,allowBlank: false
                    ,enableKeyEvents: true
                    ,anchor: '97%'
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            var s = _(f.getValue());
                            if (s == undefined) { s = f.getValue(); }
                            Ext.getCmp('modx-dashboard-widget-name-trans').setValue(s);
                            
                            Ext.getCmp('modx-dashboard-widget-header').getEl().update('<h2>'+_('widget')+': '+s+'</h2>');
                        }}
                    }
                },{
                    xtype: 'displayfield'
                    ,name: 'name_trans'
                    ,id: 'modx-dashboard-widget-name-trans'
                },{
                    name: 'description'
                    ,id: 'modx-dashboard-widget-description'
                    ,xtype: 'textfield'
                    ,fieldLabel: _('description')
                    ,description: _('widget_description_desc')
                    ,anchor: '97%'
                    ,enableKeyEvents: true
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            var s = _(f.getValue());
                            if (s == undefined) { s = f.getValue(); }
                            Ext.getCmp('modx-dashboard-widget-description-trans').setValue(s);
                        }}
                    }
                },{
                    xtype: 'displayfield'
                    ,name: 'description_trans'
                    ,id: 'modx-dashboard-widget-description-trans'
                },{
                    xtype: 'textfield'
                    ,name: 'type'
                    ,fieldLabel: _('widget_type')
                    ,description: _('widget_type_desc')
                    ,anchor: '97%'
                },{
                    xtype: 'modx-combo-namespace'
                    ,name: 'namespace'
                    ,hiddenName: 'namespace'
                    ,fieldLabel: _('widget_namespace')
                    ,description: _('widget_namespace_desc')
                    ,anchor: '97%'
                },{
                    xtype: 'textfield'
                    ,name: 'lexicon'
                    ,hiddenName: 'lexicon'
                    ,fieldLabel: _('lexicon')
                    ,description: _('widget_lexicon_desc')
                    ,anchor: '97%'
                },{
                    html: '<hr /><h4>'+_('widget_content')+'</h4>'
                    ,border: false
                },{
                    xtype: 'textarea'
                    ,name: 'content'
                    ,hideLabel: true
                    ,anchor: '97%'
                    ,width: '95%'
                    ,height: 400
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.DashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.DashboardWidget,MODx.FormPanel,{
    setup: function() {
        if (this.config.id === '' || this.config.id == undefined) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
        Ext.get('modx-dashboard-widget-header').update('<h2>'+_('widget')+': '+this.config.record.name_trans+'</h2>');

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
            //widgets: Ext.getCmp('modx-grid-dashboard-widget-placements').encode()
        });
    }
    ,success: function(o) {
        if (Ext.isEmpty(this.config['dashboard'])) {
            location.href = '?a='+MODx.actions['system/dashboards/widget/update']+'&id='+o.result.object.id;
        } else {
            Ext.getCmp('modx-btn-save').setDisabled(false);
            //Ext.getCmp('modx-grid-dashboard-widget-placements').getStore().commitChanges();

        }
    }
});
Ext.reg('modx-panel-dashboard-widget',MODx.panel.DashboardWidget);