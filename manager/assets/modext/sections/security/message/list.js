/**
 * @class MODx.page.Messages
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-messages
 */
MODx.page.Messages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-messages'
            ,view_access: config.view_access
        }]
    });
    MODx.page.Messages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Messages,MODx.Component);
Ext.reg('modx-page-messages',MODx.page.Messages);
