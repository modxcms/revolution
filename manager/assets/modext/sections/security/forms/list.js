Ext.onReady(function() {
	MODx.load({ xtype: 'modx-page-actiondom' });
});

MODx.page.ActionDom = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-actiondom'
            ,renderTo: 'modx-panel-actiondom-div'
        }]
	});
	MODx.page.ActionDom.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.ActionDom,MODx.Component);
Ext.reg('modx-page-actiondom',MODx.page.ActionDom);