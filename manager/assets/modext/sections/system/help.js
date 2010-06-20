Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-help' });
});
/**
 * Loads the help page
 * 
 * @class MODx.page.Help
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-help
 */
MODx.page.Help = function(config) {
	config = config || {};
	Ext.applyIf(config,{
            tabs: [{contentEl: 'modx-tab-about', title: _('about_title')}]
	});
	MODx.page.Help.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Help,MODx.Component);
Ext.reg('modx-page-help',MODx.page.Help);