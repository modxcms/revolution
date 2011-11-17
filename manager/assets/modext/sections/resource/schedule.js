Ext.onReady(function() {
	MODx.add('modx-page-resource-schedule');
});

/**
 * Loads the Site Schedule page
 * 
 * @class MODx.page.ResourceSchedule
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-resource-schedule
 */
MODx.page.ResourceSchedule = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-resource-schedule'
        }]
	});
	MODx.page.ResourceSchedule.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ResourceSchedule,MODx.Component);
Ext.reg('modx-page-resource-schedule',MODx.page.ResourceSchedule);
