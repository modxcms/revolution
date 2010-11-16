Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-form-customization' });
});

MODx.page.FormCustomization = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-fc-profiles'
            ,renderTo: 'modx-panel-fc-profiles-div'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.FormCustomization.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.FormCustomization,MODx.Component);
Ext.reg('modx-page-form-customization',MODx.page.FormCustomization);