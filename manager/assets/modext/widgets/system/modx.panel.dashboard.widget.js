MODx.panel.DashboardWidget = function(config) {
    config = config || {};

    var itms = [];
    itms.push({
        title: _('general_information')
        ,cls: 'main-wrapper'
        ,border: false
        ,defaults: { border: false ,msgTarget: 'side' }
        ,layout: 'form'
        ,id: 'modx-dashboard-widget-form'
        ,labelAlign: 'top'
        ,items: [{
            layout: 'column'
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
                // ,cls:'main-wrapper'
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: .6
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
                    ,anchor: '100%'
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            var s = _(f.getValue());
                            if (s == undefined) { s = f.getValue(); }
                            Ext.getCmp('modx-dashboard-widget-name-trans').setValue(s);
                            if (!Ext.isEmpty(s)) {
                                Ext.getCmp('modx-dashboard-widget-header').getEl().update(_('widget')+': '+s);
                            }
                        }}
                    }
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-name'
                    ,html: _('widget_name_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'displayfield'
                    ,hideLabel: true
                    ,name: 'name_trans'
                    ,cls: 'desc-under desc-trans'
                    ,id: 'modx-dashboard-widget-name-trans'
                },{
                    name: 'description'
                    ,id: 'modx-dashboard-widget-description'
                    ,xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,description: _('widget_description_desc')
                    ,anchor: '100%'
                    ,enableKeyEvents: true
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            var s = _(f.getValue());
                            if (s == undefined) { s = f.getValue(); }
                            Ext.getCmp('modx-dashboard-widget-description-trans').setValue(s);
                        }}
                    }
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-description'
                    ,html: _('widget_description_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'displayfield'
                    ,hideLabel: true
                    ,name: 'description_trans'
                    ,cls: 'desc-under desc-trans'
                    ,id: 'modx-dashboard-widget-description-trans'
                },{
                    xtype: 'modx-combo-dashboard-widget-type'
                    ,id: 'modx-dashboard-widget-type'
                    ,name: 'type'
                    ,hiddenName: 'type'
                    ,fieldLabel: _('widget_type')
                    ,description: _('widget_type_desc')
                    ,anchor: '100%'
                    ,value: config.record.type || 'html'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-type'
                    ,html: _('widget_type_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .4
                ,items: [{
                    xtype: 'modx-combo-dashboard-widget-size'
                    ,name: 'size'
                    ,hiddenName: 'size'
                    ,id: 'modx-dashboard-widget-size'
                    ,fieldLabel: _('widget_size')
                    ,description: _('widget_size_desc')
                    ,anchor: '100%'
                    ,value: config.record.size || 'half'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-size'
                    ,html: _('widget_size_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-namespace'
                    ,name: 'namespace'
                    ,hiddenName: 'namespace'
                    ,id: 'modx-dashboard-widget-namespace'
                    ,fieldLabel: _('widget_namespace')
                    ,description: _('widget_namespace_desc')
                    ,anchor: '100%'
                    ,value: config.record.namespace || 'core'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-namespace'
                    ,html: _('widget_namespace_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,name: 'lexicon'
                    ,hiddenName: 'lexicon'
                    ,id: 'modx-dashboard-widget-lexicon'
                    ,fieldLabel: _('lexicon')
                    ,description: _('widget_lexicon_desc')
                    ,anchor: '100%'
                    ,value: config.record.lexicon || 'core:dashboards'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-dashboard-widget-lexicon'
                    ,html: _('widget_lexicon_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'panel'
            ,border: false
            ,layout: 'form'
            // ,cls:'main-wrapper'
            ,style: 'padding-top: 15px' // new form panel, first label is not gonna have top padding
            ,labelAlign: 'top'
            ,items: [/*{
                html: '<h4>'+_('widget_content')+'</h4>'
                ,border: false
                ,anchor: '100%'
             },*/{
                xtype: 'textarea'
                ,name: 'content'
                ,fieldLabel: _('widget_content')
                // ,hideLabel: true
                ,anchor: '100%'
                ,height: 400
            }]
        }]
    });
    if (!Ext.isEmpty(config.record.id)) {
        itms.push({
            title: _('dashboards')
            ,hideMode: 'offsets'
            ,id: 'modx-panel-widget-dashboards'
            ,items: [{
                html: '<p>'+_('widget_dashboards.intro_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-dashboard-widget-dashboards'
                ,cls: 'main-wrapper'
                ,preventRender: true
                ,widget: config.record.id
                ,autoHeight: true
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/dashboard/widget/update'
        }
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: _('widget_new')
            ,id: 'modx-dashboard-widget-header'
            ,xtype: 'modx-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: false
            }
            //,border: true
            ,id: 'modx-dashboard-widget-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-dashboard-widget-tabpanel' + ((Ext.isEmpty(config.record.id)) ? '-new' : '')
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
    initialized: false
    ,setup: function() {
        if (this.initialized) { return false; }
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
        Ext.defer(function() {
            Ext.get('modx-dashboard-widget-header').update(_('widget')+': '+this.config.record.name_trans);
        }, 250, this);

        var d = this.config.record.dashboards;
        var g = Ext.getCmp('modx-grid-dashboard-widget-dashboards');
        if (d && g) {
            g.getStore().loadData(d);
        }

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
        this.initialized = true;
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
        if (Ext.isEmpty(this.config.record) || Ext.isEmpty(this.config.record.id)) {
            MODx.loadPage('system/dashboards/widget/update', 'id='+o.result.object.id);
        } else {
            Ext.getCmp('modx-abtn-save').setDisabled(false);
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
        ,url: MODx.config.connector_url
        ,action: 'system/dashboard/getList'
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


/* seems unused */
MODx.window.WidgetAddDashboard = function(config) {
    config = config || {};
    this.ident = config.ident || 'dbugadd'+Ext.id();
    Ext.applyIf(config,{
        title: _('widget_place')
        // ,frame: true
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
