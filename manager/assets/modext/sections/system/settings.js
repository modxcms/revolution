Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-system-settings' });
});
/**
 * Loads the configuration page
 * 
 * @class MODx.page.SystemSettings
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-settings
 */
MODx.page.SystemSettings = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-system-settings'
            ,renderTo: 'modx-panel-system-settings-div'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,handler: MODx.loadHelpPane
        }]
    });
	MODx.page.SystemSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemSettings,MODx.Component);
Ext.reg('modx-page-system-settings',MODx.page.SystemSettings);