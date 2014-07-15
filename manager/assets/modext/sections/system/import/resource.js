MODx.page.ImportResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-import-resources'
        ,buttons: [{
            process: 'system/import/index'
            ,text: _('import_resources')
            ,id: 'modx-abtn-import'
            ,cls: 'primary-button'
            ,method: 'remote'
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        }]
        ,components: [{
            xtype: 'modx-panel-import-resources'
        }]
    });
    MODx.page.ImportResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ImportResource,MODx.Component);
Ext.reg('modx-page-import-resource',MODx.page.ImportResource);
