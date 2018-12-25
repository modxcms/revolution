/**
 * Loads the trash page
 *
 * @class MODx.page.Trash
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-trash
 */
MODx.page.Trash = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'modx-panel-trash'
        }]
    });
    MODx.page.Trash.superclass.constructor.call(this, config);
};
Ext.extend(MODx.page.Trash, MODx.Component);
Ext.reg('modx-page-trash', MODx.page.Trash);
