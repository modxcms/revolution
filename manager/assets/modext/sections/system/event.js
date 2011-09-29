Ext.onReady(function() {
    MODx.add('modx-page-system-event');
});
/**
 * Loads the system event page
 * 
 * @class MODx.page.SystemEvent
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-system-event
 */
MODx.page.SystemEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'modx-panel-error-log'
        }]
    });
    MODx.page.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.SystemEvent,MODx.Component);
Ext.reg('modx-page-system-event',MODx.page.SystemEvent);