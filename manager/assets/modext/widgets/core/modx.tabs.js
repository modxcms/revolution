MODx.Tabs = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		enableTabScroll: true
        ,layoutOnTabChange: true
        ,plain: true
        ,deferredRender: true
        ,hideMode: 'offsets'
		,defaults: {
			autoHeight: true
            ,hideMode: 'offsets'
            ,border: true
            ,autoWidth: true
			,bodyCssClass: 'tab-panel-wrapper'
		}
	    ,activeTab: 0
        ,border: false
        ,autoScroll: true
        ,autoHeight: true
        ,cls: 'modx-tabs'
	});
	MODx.Tabs.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);

MODx.VerticalTabs = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		cls: 'vertical-tabs-panel'
		,headerCfg: { tag: 'div', cls: 'x-tab-panel-header vertical-tabs-header' }
		,bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' }
		,defaults: {
			bodyCssClass: 'vertical-tabs-body'
            ,autoScroll: true
            ,autoHeight: true
            ,autoWidth: true
			,layout: 'form'
		}
	});
	MODx.VerticalTabs.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.VerticalTabs, MODx.Tabs);
Ext.reg('modx-vtabs',MODx.VerticalTabs);