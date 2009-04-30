/**
 * Loads a grid of System Events
 * 
 * @class MODx.grid.SystemEvent
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-system-event
 */
MODx.grid.SystemEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('system_events')
        ,url: MODx.config.connectors_url+'system/event.php'
        ,fields: ['id','name','service','groupname','menu']
        ,autosave: true
        ,paging: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 80
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
        }]
    });
    MODx.grid.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SystemEvent,MODx.grid.Grid);
Ext.reg('modx-grid-system-event',MODx.grid.SystemEvent);