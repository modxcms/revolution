/**
 * Loads the update symlink resource page
 *
 * @class MODx.page.UpdateSymLink
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-symlink-update
 */
MODx.page.UpdateSymLink = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-symlink'
            ,renderTo: 'modx-panel-symlink-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
    });
    MODx.page.UpdateSymLink.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateSymLink,MODx.page.UpdateResource);
Ext.reg('modx-page-symlink-update',MODx.page.UpdateSymLink);
