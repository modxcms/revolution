/**
 * @class MODx.panel.Dashboard
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-dashboard
 */
MODx.panel.Dashboard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-dashboard'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Dashboard/Update'
        }
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [this.getPageHeader(config),{
            xtype: 'modx-tabs'
            ,defaults: {
                autoHeight: true
                ,border: false
            }
            ,id: 'modx-dashboard-tabs'
            ,forceLayout: true
            ,deferredRender: false
            ,stateful: true
            ,stateId: 'modx-dashboard-tabpanel'
            ,stateEvents: ['tabchange']
            ,getState:function() {
                return {activeTab:this.items.indexOf(this.getActiveTab())};
            }
            // todo: the layout is inconsistent with other panels, refactor the structure
            ,items: [{
                title: _('general_information')
                ,cls: 'form-with-labels'
                ,defaults: { border: false, cls: 'main-wrapper' }
                ,layout: 'form'
                ,id: 'modx-dashboard-form'
                ,labelAlign: 'top'
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'modx-dashboard-id'
                    ,value: config.record.id
                },{
                    layout: 'column'
                    ,border: false
                    ,defaults: {
                        layout: 'form'
                        ,labelAlign: 'top'
                        ,anchor: '100%'
                        ,border: false
                    }
                    ,items: [{
                        columnWidth: .7
                        ,cls: 'main-content'
                        ,items: [{
                            name: 'name'
                            ,id: 'modx-dashboard-name'
                            ,xtype: 'textfield'
                            ,fieldLabel: _('name')
                            ,description: MODx.expandHelp ? '' : _('dashboard_desc_name')
                            ,allowBlank: false
                            ,enableKeyEvents: true
                            ,anchor: '100%'
                            ,listeners: {
                                'keyup': {scope:this,fn:function(f,e) {
                                    Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(f.getValue()));
                                }}
                            }
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'modx-dashboard-name'
                            ,html: _('dashboard_desc_name')
                            ,cls: 'desc-under'
                        },{
                            name: 'description'
                            ,id: 'modx-dashboard-description'
                            ,xtype: 'textarea'
                            ,fieldLabel: _('description')
                            ,description: MODx.expandHelp ? '' : _('dashboard_desc_description')
                            ,anchor: '100%'
                            ,grow: true
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'modx-dashboard-description'
                            ,html: _('dashboard_desc_description')
                            ,cls: 'desc-under'
                        }]
                    },{
                        columnWidth: .3
                        ,cls: 'main-content'
                        ,items: [{
                            name: 'hide_trees'
                            ,id: 'modx-dashboard-hide-trees'
                            ,xtype: 'xcheckbox'
                            ,boxLabel: _('dashboard_hide_trees')
                            ,description: MODx.expandHelp ? '' : _('dashboard_desc_hide_trees')
                            ,inputValue: 1
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'modx-dashboard-hide-trees'
                            ,html: _('dashboard_desc_hide_trees')
                            ,cls: 'desc-under'
                        },{
                            name: 'customizable'
                            ,id: 'modx-dashboard-customizable'
                            ,xtype: 'xcheckbox'
                            ,boxLabel: _('dashboard_customizable')
                            ,description: MODx.expandHelp ? '' : _('dashboard_desc_customizable')
                            ,inputValue: 1
                            ,checked: true
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'modx-dashboard-customizable'
                            ,html: _('dashboard_desc_customizable')
                            ,cls: 'desc-under'
                        }]
                    }]
                },{
                    html: '<p>'+_('dashboard_widgets.intro_msg')+'</p>'
                    ,xtype: 'modx-description'
                },{
                    xtype: 'modx-grid-dashboard-widget-placements'
                    ,preventRender: true
                    ,dashboard: config.record.id
                    ,autoHeight: true
                    ,anchor: '100%'
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
    MODx.panel.Dashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Dashboard,MODx.FormPanel,{
    initialized: false

    ,setup: function() {
        if (this.initialized) { return false; }
        if (Ext.isEmpty(this.config.record.id)) {
            this.fireEvent('ready');
            return false;
        }
        this.getForm().setValues(this.config.record);
        Ext.getCmp('modx-header-breadcrumbs').updateHeader(Ext.util.Format.htmlEncode(this.config.record.name));

        var d = this.config.record.widgets;
        var g = Ext.getCmp('modx-grid-dashboard-widget-placements');
        if (d && g) {
            g.getStore().loadData(d);
        }

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
        this.initialized = true;
    }

    ,beforeSubmit: function(o) {
        var bp = {};
        var wg = Ext.getCmp('modx-grid-dashboard-widget-placements');
        if (wg) {
            bp['widgets'] = wg.encode();
        }
        Ext.apply(o.form.baseParams,bp);
    }

    ,success: function(o) {
        if (Ext.isEmpty(this.config.record) || Ext.isEmpty(this.config.record.id)) {
            MODx.loadPage('system/dashboards/update', 'id='+o.result.object.id);
        } else {
            Ext.getCmp('modx-abtn-save').setDisabled(false);
            var wg = Ext.getCmp('modx-grid-dashboard-widget-placements');
            if (wg) { wg.getStore().commitChanges(); }

        }
    }

    ,getPageHeader: function(config) {
        return MODx.util.getHeaderBreadCrumbs('modx-dashboard-header', [{
            text: _('dashboards'),
            href: MODx.getPage('system/dashboards')
        }]);
    }
});
Ext.reg('modx-panel-dashboard',MODx.panel.Dashboard);

/**
 * @class MODx.grid.DashboardWidgetPlacements
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-dashboard-widget-placements
 */
MODx.grid.DashboardWidgetPlacements = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description_trans}</p>'
        )
    });
    Ext.applyIf(config,{
        id: 'modx-grid-dashboard-widget-placements'
        ,url: MODx.config.connector_url
        ,action: 'system/dashboard/widget/placement/getList'
        ,fields: ['dashboard','widget','rank','name','name_trans','description','description_trans']
        ,autoHeight: true
        ,primaryKey: 'widget'
        ,cls: 'modx-grid modx-grid-draggable'
        ,plugins: [this.exp,new Ext.ux.dd.GridDragDropRowOrder({
            copy: false // false by default
            ,scrollable: true // enable scrolling support (default is false)
            ,targetCfg: {}
            ,listeners: {
                'afterrowmove': {fn:this.onAfterRowMove,scope:this}
            }
        })]
        ,columns: [this.exp,{
            header: _('widget')
            ,dataIndex: 'name_trans'
            ,width: 600
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=system/dashboards/widget/update&id=' + record.data.widget
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('rank')
            ,dataIndex: 'rank'
            ,width: 80
            ,editor: { xtype: 'numberfield', allowBlank: false, allowNegative: false }
        }]
        ,tbar: [{
            text: _('widget_place')
            ,cls:'primary-button'
            ,handler: this.placeWidget
            ,scope: this
        }]
    });
    MODx.grid.DashboardWidgetPlacements.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(['dashboard','widget','rank','name','name_trans','description','description_trans']);
};
Ext.extend(MODx.grid.DashboardWidgetPlacements,MODx.grid.LocalGrid,{
    getMenu: function() {
        return [{
            text: _('widget_unplace')
            ,handler: this.unplaceWidget
            ,scope: this
        }];
    }

    ,onAfterRowMove: function(dt,sri,ri,sels) {
        var s = this.getStore();
        var sourceRec = s.data.items[sri];
        var total = s.data.length;

        sourceRec.set('rank',sri);
        sourceRec.commit();

        /* get all rows below ri, and up their rank by 1 */
        var brec;
        for (var x=(ri-1);x<total;x++) {
            brec = s.data.items[x];
            if (brec) {
                brec.set('rank',x);
                brec.commit();
            }
        }
        return true;
    }

    ,unplaceWidget: function(btn,e) {
        var rec = this.getSelectionModel().getSelected();
        var s = this.getStore();
        var idx = s.indexOf(rec);
        var total = s.getTotalCount();
        var r,x;
        for (x=idx;x<total;x++) {
            r = s.getAt(x);
            if (r) {
                r.set('rank',r.get('rank')-1);
                r.commit();
            }
        }
        s.remove(rec);
    }

    ,placeWidget: function(btn,e) {
        if (!this.windows.placeWidget) {
            this.windows.placeWidget = MODx.load({
                xtype: 'modx-window-dashboard-widget-place'
                ,listeners: {
                    'success': {fn:function(vs) {
                        var rec = new this.propRecord(vs);
                        this.getStore().add(rec);
                    },scope:this}
                }
            });
        }
        this.windows.placeWidget.reset();
        this.windows.placeWidget.setValues({
            dashboard: this.config.dashboard
        });
        this.windows.placeWidget.show(btn);
    }
});
Ext.reg('modx-grid-dashboard-widget-placements',MODx.grid.DashboardWidgetPlacements);

/**
 * @class MODx.window.DashboardWidgetPlace
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-dashboard-widget-place
 */
MODx.window.DashboardWidgetPlace = function(config) {
    config = config || {};
    this.ident = config.ident || 'dbugadd'+Ext.id();
    Ext.applyIf(config,{
        title: _('widget_place')
        ,id: 'modx-window-dashboard-widget-place'
        ,fields: [{
            xtype: 'modx-combo-dashboard-widgets'
            ,fieldLabel: _('widget')
            ,name: 'widget'
            ,hiddenName: 'widget'
            ,id: 'modx-'+this.ident+'-widget'
            ,allowBlank: false
            ,msgTarget: 'under'
            ,anchor: '100%'
        }]
    });
    MODx.window.DashboardWidgetPlace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DashboardWidgetPlace,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var fld = f.findField('widget');
        var g = Ext.getCmp('modx-grid-dashboard-widget-placements');
        var s = g.getStore();
        if (s.find('widget',fld.getValue()) != -1) {
            fld.markInvalid(_('dashboard_widget_err_placed'));
            return false;
        }
        var rank =  s.data.length > 0
            // Get the rank of the last record
            ? s.data.items[s.data.length - 1].get('rank') + 1
            // Or set it to '0' if no record found
            : 0;

        var fldStore = fld.getStore();
        var fldRi = fldStore.find('id',fld.getValue());
        var rec = fldStore.getAt(fldRi);

        if (id != '' && this.fp.getForm().isValid()) {

            if (this.fireEvent('success',{
                widget: fld.getValue()
                ,dashboard: g.config.dashboard
                ,name: rec.data.name
                ,name_trans: rec.data.name_trans
                ,description: rec.data.description
                ,description_trans: rec.data.description_trans
                ,rank: rank
            })) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        } else {
            MODx.msg.alert(_('error'),_('widget_err_ns'));
        }
        return true;
    }
});
Ext.reg('modx-window-dashboard-widget-place',MODx.window.DashboardWidgetPlace);

/*
MODx.grid.DashboardUserGroups = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-dashboard-usergroups'
        ,url: MODx.config.connector_url
        ,action: 'system/dashboard/group/getList'
        ,fields: ['id','name']
        ,autoHeight: true
        ,primaryKey: 'user'
        ,columns: [{
            header: _('user_group')
            ,dataIndex: 'name'
            ,width: 600
        }]
        ,tbar: [{
            text: _('dashboard_usergroup_add')
            ,handler: this.addUserGroup
            ,scope: this
        }]
    });
    MODx.grid.DashboardUserGroups.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(['id','name']);
};
Ext.extend(MODx.grid.DashboardUserGroups,MODx.grid.LocalGrid,{
    getMenu: function() {
        return [{
            text: _('dashboard_usergroup_remove')
            ,handler: this.remove.createDelegate(this,[{
                title: _('dashboard_usergroup_remove')
                ,text: _('dashboard_usergroup_remove_confirm')
            }])
            ,scope: this
        }];
    }

    ,addUserGroup: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'modx-window-dashboard-usergroup-add'
            ,listeners: {
                'success': {fn:function(vs) {
                    var rec = new this.propRecord(vs);
                    this.getStore().add(rec);
                },scope:this}
            }
        });
        var w = Ext.getCmp('modx-window-dashboard-usergroup-add');
        w.reset();
        w.setValues({
            dashboard: this.config.dashboard
        });

    }
});
Ext.reg('modx-grid-dashboard-usergroups',MODx.grid.DashboardUserGroups);

MODx.window.DashboardUserGroupAdd = function(config) {
    config = config || {};
    this.ident = config.ident || 'dbugadd'+Ext.id();
    Ext.applyIf(config,{
        title: _('dashboard_usergroup_add')
        ,frame: true
        ,id: 'modx-window-dashboard-usergroup-add'
        ,fields: [{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'usergroup'
            ,hiddenName: 'usergroup'
            ,id: 'modx-'+this.ident+'-usergroup'
            ,allowBlank: false
        }]
    });
    MODx.window.DashboardUserGroupAdd.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DashboardUserGroupAdd,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var fld = f.findField('usergroup');

        if (id != '' && this.fp.getForm().isValid()) {
            if (this.fireEvent('success',{
                id: fld.getValue()
                ,name: fld.getRawValue()
            })) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        } else {
            MODx.msg.alert(_('error'),_('user_group_err_ns'));
        }
        return true;
    }
});
Ext.reg('modx-window-dashboard-usergroup-add',MODx.window.DashboardUserGroupAdd);
*/

/**
 * @class MODx.combo.DashboardWidgets
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-dashboard-widgets
 */
MODx.combo.DashboardWidgets = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'widget'
        ,hiddenName: 'widget'
        ,displayField: 'name_trans'
        ,editable: true
        ,valueField: 'id'
        ,fields: ['id','name','name_trans','description','description_trans']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Dashboard/Widget/GetList'
            ,combo: true
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name_trans:htmlEncode}</h4>'
            ,'<p class="modx-combo-desc">{description_trans:htmlEncode}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.DashboardWidgets.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.DashboardWidgets,MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard-widgets',MODx.combo.DashboardWidgets);
