MODx.panel.DashboardWidget = function(config) {
    config = config || {};

    var itms = [];
    itms.push({
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
                    if (!Ext.isEmpty(s)) {
                        Ext.getCmp('modx-dashboard-widget-header').getEl().update('<h2>'+_('widget')+': '+s+'</h2>');
                    }
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
            xtype: 'modx-combo-dashboard-widget-type'
            ,name: 'type'
            ,hiddenName: 'type'
            ,fieldLabel: _('widget_type')
            ,description: _('widget_type_desc')
            ,anchor: '97%'
            ,value: 'html'
        },{
            xtype: 'modx-combo-dashboard-widget-size'
            ,name: 'size'
            ,hiddenName: 'size'
            ,fieldLabel: _('widget_size')
            ,description: _('widget_size_desc')
            ,anchor: '97%'
            ,value: 'half'
        },{
            xtype: 'modx-combo-namespace'
            ,name: 'namespace'
            ,hiddenName: 'namespace'
            ,fieldLabel: _('widget_namespace')
            ,description: _('widget_namespace_desc')
            ,anchor: '97%'
            ,value: 'core'
        },{
            xtype: 'textfield'
            ,name: 'lexicon'
            ,hiddenName: 'lexicon'
            ,fieldLabel: _('lexicon')
            ,description: _('widget_lexicon_desc')
            ,anchor: '97%'
            ,value: 'core:dashboards'
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
    });
    if (!Ext.isEmpty(config.record.id)) {
        itms.push({
            title: _('dashboards')
            ,hideMode: 'offsets'
            ,id: 'modx-panel-widget-dashboards'
            ,items: [{
                html: '<p>'+_('widget_dashboards.intro_msg')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-dashboard-widget-dashboards'
                ,preventRender: true
                ,widget: config.record.id
                ,autoHeight: true
                ,width: '97%'
                ,listeners: {
                    'afterRemoveRow': {fn:this.markDirty,scope:this}
                    ,'updateRole': {fn:this.markDirty,scope:this}
                    ,'addMember': {fn:this.markDirty,scope:this}
                }
            }]
        });
    }

    Ext.applyIf(config,{
        id: 'modx-panel-dashboard-widget'
        ,url: MODx.config.connectors_url+'system/dashboard/widget.php'
        ,baseParams: {
            action: 'update'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: '<h2>'+_('widget_new')+'</h2>'
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
            ,items: itms
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
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
        Ext.get('modx-dashboard-widget-header').update('<h2>'+_('widget')+': '+this.config.record.name_trans+'</h2>');

        var d = this.config.record.dashboards;
        var g = Ext.getCmp('modx-grid-dashboard-widget-dashboards');
        if (d && g) {
            g.getStore().loadData(d);
        }

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-dashboard-widget-dashboards');
        if (g) {
            Ext.apply(o.form.baseParams,{
                dashboards: g.encode()
            });
        }
    }
    ,success: function(o) {
        if (Ext.isEmpty(this.config['dashboard'])) {
            location.href = '?a='+MODx.action['system/dashboards/widget/update']+'&id='+o.result.object.id;
        } else {
            Ext.getCmp('modx-btn-save').setDisabled(false);
            var g = Ext.getCmp('modx-grid-dashboard-widget-dashboards');
            if (g) { g.getStore().commitChanges(); }

        }
    }
});
Ext.reg('modx-panel-dashboard-widget',MODx.panel.DashboardWidget);


MODx.grid.DashboardWidgetDashboards = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-dashboard-widget-dashboards'
        ,url: MODx.config.connectors_url+'system/dashboard.php'
        ,action: 'getList'
        ,fields: ['id','name','description']
        ,autoHeight: true
        ,primaryKey: 'widget'
        ,columns: [{
            header: _('dashboard')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
        }]
    });
    MODx.grid.DashboardWidgetDashboards.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(['id','name','description']);
};
Ext.extend(MODx.grid.DashboardWidgetDashboards,MODx.grid.LocalGrid);
Ext.reg('modx-grid-dashboard-widget-dashboards',MODx.grid.DashboardWidgetDashboards);



MODx.window.WidgetAddDashboard = function(config) {
    config = config || {};
    this.ident = config.ident || 'dbugadd'+Ext.id();
    Ext.applyIf(config,{
        title: _('widget_place')
        ,frame: true
        ,id: 'modx-window-widget-add-dashboard'
        ,fields: [{
            xtype: 'modx-combo-dashboard'
            ,fieldLabel: _('dashboard')
            ,name: 'dashboard'
            ,hiddenName: 'dashboard'
            ,id: 'modx-'+this.ident+'-dashboard'
            ,allowBlank: false
            ,msgTarget: 'under'
        }]
    });
    MODx.window.WidgetAddDashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.WidgetAddDashboard,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var fld = f.findField('widget');
        var g = Ext.getCmp('modx-grid-dashboard-widget-dashboards');
        var s = g.getStore();
        if (s.find('widget',fld.getValue()) != -1) {
            fld.markInvalid(_('dashboard_widget_err_placed'));
            return false;
        }

        if (id != '' && this.fp.getForm().isValid()) {
            var r = s.getTotalCount();

            if (this.fireEvent('success',{
                widget: fld.getValue()
                ,dashboard: g.config.dashboard
                ,name: fld.getRawValue()
                ,rank: r
            })) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        } else {
            MODx.msg.alert(_('error'),_('dashboard_err_ns'));
        }
        return true;
    }
});
Ext.reg('modx-window-widget-add-dashboard',MODx.window.WidgetAddDashboard);

MODx.combo.DashboardWidgetType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                [_('widget_html'),'html']
                ,[_('widget_file'),'file']
                ,[_('widget_snippet'),'snippet']
                ,[_('widget_php'),'php']
            ]
        })
        ,name: 'type'
        ,hiddenName: 'type'
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    MODx.combo.DashboardWidgetType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.DashboardWidgetType,MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard-widget-type',MODx.combo.DashboardWidgetType);


MODx.combo.DashboardWidgetSize = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                [_('widget_size_half'),'half']
                ,[_('widget_size_full'),'full']
                ,[_('widget_size_double'),'double']
            ]
        })
        ,name: 'size'
        ,hiddenName: 'size'
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    MODx.combo.DashboardWidgetSize.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.DashboardWidgetSize,MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard-widget-size',MODx.combo.DashboardWidgetSize);