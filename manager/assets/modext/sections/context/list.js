/**
 * Loads the context management page
 * 
 * @class MODx.page.Context
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-contexts
 */
MODx.page.Contexts = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'modx-panel-contexts'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.Contexts.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Contexts,MODx.Component);
Ext.reg('modx-page-contexts',MODx.page.Contexts);    
