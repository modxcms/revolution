/**
 * Loads the dashboards page
 *
 * @class MODx.page.Dashboards
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-dashboards
 */
MODx.page.Dashboards = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
            xtype: 'modx-panel-dashboards'
        }]
        ,buttons: [{
            text: _('help_ex')
            ,id: 'modx-abtn-help'
            ,handler: MODx.loadHelpPane
        }]
	});
	MODx.page.Dashboards.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Dashboards,MODx.Component);
Ext.reg('modx-page-dashboards',MODx.page.Dashboards);
