MODx.page.CreateDashboardWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-dashboard-widget'
        ,actions: {
            'new': 'System/Dashboard/Widget/Create'
            ,edit: 'System/Dashboard/Widget/Update'
            ,cancel: 'system/dashboards'
        }
        ,buttons: [{
            process: 'System/Dashboard/Widget/Create'
            ,reload: true
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
            ,handler: function() {
                MODx.loadPage('system/dashboards');
            }
        },{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
        ,components: [{
            xtype: 'modx-panel-dashboard-widget'
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateDashboardWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateDashboardWidget,MODx.Component);
Ext.reg('modx-page-dashboard-widget-create',MODx.page.CreateDashboardWidget);
