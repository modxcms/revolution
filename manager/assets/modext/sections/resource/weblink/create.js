/**
 * Loads the create resource page
 *
 * @class MODx.page.CreateWebLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-weblink-create
 */
MODx.page.CreateWebLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-weblink'
            ,renderTo: 'modx-panel-weblink-div'
            ,resource: 0
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.CreateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateWebLink,MODx.page.CreateResource);
Ext.reg('modx-page-weblink-create',MODx.page.CreateWebLink);
