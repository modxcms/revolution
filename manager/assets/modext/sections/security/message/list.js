Ext.onReady(function() {
    MODx.load({ xtype: 'modx-page-messages' });
});

/**
 * @class MODx.page.Messages
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-page-messages
 */
MODx.page.Messages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        tabs: [{
            contentEl: 'modx-tab-list', title: _('messages')
        }]
        ,components: [{
            xtype: 'modx-grid-message'
            ,renderTo: 'modx-grid-message'
        }]
    });
    MODx.page.Messages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Messages,MODx.Component);
Ext.reg('modx-page-messages',MODx.page.Messages);