MODx.page.FormCustomization = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-fc-profiles'
        }]
        ,buttons: [{
            text: '<i class="icon icon-question-circle"></i>'
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
    });
    MODx.page.FormCustomization.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.FormCustomization,MODx.Component);
Ext.reg('modx-page-form-customization',MODx.page.FormCustomization);
