/**
 * Loads the welcome page
 * 
 * @class MODx.page.Welcome
 * @extends MODx.Component
 * @param {Object} config An object of configuration options
 * @xtype page-welcome
 */
MODx.page.Welcome = function(config) {
	config = config || {}; 
	Ext.applyIf(config,{
		components: [{
            xtype: 'modx-panel-welcome'
            ,renderTo: 'modx-panel-welcome-div'
            ,displayConfigCheck: config.displayConfigCheck
            ,user: MODx.user.id
        }]
	});
    MODx.page.Welcome.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Welcome,MODx.Component);
Ext.reg('modx-page-welcome',MODx.page.Welcome);