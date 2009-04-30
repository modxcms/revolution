/**
 * Custom class that extends Ext2.0 tab functionality for MODx.
 * 
 * @class MODx.Tabs
 * @extends Ext.TabPanel
 * @constructor
 * @param {Object} config A configuration object.
 * @xtype modx-tabs
 */
MODx.Tabs = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		enableTabScroll: true
		,resizeTabs: true
        ,layoutOnTabChange: true
        ,plain: true
		,defaults: {
			autoScroll: true
			,autoHeight: true
            ,hideMode: 'offsets'
		}
	    ,activeTab: 0
        ,border: false
        ,cls: 'modx-tabs'
	});
	MODx.Tabs.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);