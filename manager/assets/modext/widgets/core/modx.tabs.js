MODx.Tabs = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		enableTabScroll: true
        ,layoutOnTabChange: true
        ,plain: true
        ,deferredRender: true
        ,hideMode: 'offsets'
		,defaults: {
			autoScroll: true
			,autoHeight: true
            ,hideMode: 'offsets'
            ,border: true
            ,autoWidth: true
		}
	    ,activeTab: 0
        ,border: false
        ,autoHeight: true
        ,cls: 'modx-tabs'
	});
	MODx.Tabs.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);