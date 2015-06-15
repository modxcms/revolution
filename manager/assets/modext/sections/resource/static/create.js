/**
 * Loads the create static resource page
 *
 * @class MODx.page.CreateStatic
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-static-create
 */
MODx.page.CreateStatic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-static'
            ,renderTo: 'modx-panel-static-div'
            ,resource: 0
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.CreateStatic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateStatic,MODx.page.CreateResource);
Ext.reg('modx-page-static-create',MODx.page.CreateStatic);
