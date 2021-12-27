MODx.page.CreateDashboard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-dashboard'
        ,actions: {
            'new': 'System/Dashboard/Create'
            ,edit: 'System/Dashboard/Update'
            ,cancel: 'system/dashboards'
        }
        ,buttons: [{
            process: 'System/Dashboard/Create'
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
            xtype: 'modx-panel-dashboard'
            ,record: config.record || {}
        }]
    });
    MODx.page.CreateDashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateDashboard,MODx.Component);
Ext.reg('modx-page-dashboard-create',MODx.page.CreateDashboard);
