/**
 * Loads the update weblink resource page
 *
 * @class MODx.page.UpdateWebLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-weblink-update
 */
MODx.page.UpdateWebLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-weblink'
            ,renderTo: 'modx-panel-weblink-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.UpdateWebLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateWebLink,MODx.page.UpdateResource);
Ext.reg('modx-page-weblink-update',MODx.page.UpdateWebLink);
