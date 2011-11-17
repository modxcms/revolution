Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-import-resource' });
});

MODx.page.ImportResource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-import-resources'
        ,buttons: [{
            process: 'import', text: _('import_resources'), method: 'remote'
        },{
            process: 'cancel', text: _('cancel'), params: {a:MODx.action['welcome']}
        }]
        ,components: [{
            xtype: 'modx-panel-import-resources'
        }]
    });
    MODx.page.ImportResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ImportResource,MODx.Component);
Ext.reg('modx-page-import-resource',MODx.page.ImportResource);
