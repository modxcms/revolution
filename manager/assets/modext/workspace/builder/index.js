Ext.onReady(function() {
	MODx.load({ xtype: 'page-package-builder' });
});

/**
 * @class MODx.page.PackageBuilder
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-package-builder
 */
MODx.page.PackageBuilder = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'panel-package-builder'
            ,id: 'panel-package-builder'
            ,renderTo: 'panel-package-builder-div'
        }]
    });
    MODx.page.PackageBuilder.superclass.constructor.call(this,config);
    Ext.Ajax.timeout = 0;
};
Ext.extend(MODx.page.PackageBuilder,MODx.Component);
Ext.reg('page-package-builder',MODx.page.PackageBuilder);