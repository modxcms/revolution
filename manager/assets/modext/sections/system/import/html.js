MODx.page.ImportHTML = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-import-html'
        ,buttons: [{
            process: 'system/import/html'
            ,text: _('import_site')
            ,id: 'modx-abtn-import'
            ,cls:'primary-button'
            ,method: 'remote'
        },{
            text: _('cancel')
            ,id: 'modx-abtn-cancel'
        }]
        ,components: [{
            xtype: 'modx-panel-import-html'
        }]
    });
    MODx.page.ImportHTML.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ImportHTML,MODx.Component);
Ext.reg('modx-page-import-html',MODx.page.ImportHTML);
