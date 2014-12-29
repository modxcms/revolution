/**
 * Loads the update static resource page
 *
 * @class MODx.page.UpdateStatic
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-static-update
 */
MODx.page.UpdateStatic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-static'
            ,renderTo: 'modx-panel-static-div'
            ,resource: config.resource
            ,record: config.record || {}
            ,publish_document: config.publish_document
            ,show_tvs: config.show_tvs
            ,url: config.url
        }]
        ,buttons: this.getButtons(config)
    });
    MODx.page.UpdateStatic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.UpdateStatic,MODx.page.UpdateResource);
Ext.reg('modx-page-static-update',MODx.page.UpdateStatic);
