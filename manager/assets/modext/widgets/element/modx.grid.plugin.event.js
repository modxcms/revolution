/**
 * Loads a grid of Plugin Events
 * 
 * @class MODx.grid.PluginEvent
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-plugin-event
 */
MODx.grid.PluginEvent = function(config) {
    config = config || {};
    var ec = MODx.load({
        xtype: 'checkbox-column'
        ,header: _('enabled')
        ,dataIndex: 'enabled'
        ,editable: true
        ,width: 50
        ,sortable: false
    });

    Ext.applyIf(config,{
        title: _('system_events')
        ,id: 'modx-grid-plugin-event'
        ,url: MODx.config.connectors_url+'element/plugin/event.php'
        ,baseParams: {
            action: 'getList'
            ,plugin: config.plugin
        }
        ,saveParams: {
            plugin: config.plugin
        }
        ,fields: ['id','name','service','groupname','enabled','priority','propertyset','menu']
        ,paging: true
        ,remoteSort: true
        ,plugins: ec
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 80
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,sortable: true
        },
        ec
        ,{
            header: _('propertyset')
            ,dataIndex: 'propertyset'
            ,width: 150
            ,editor: { xtype: 'modx-combo-property-set' ,renderer: true }
        },{
            header: _('priority')
            ,dataIndex: 'priority'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,name: 'name_filter'
            ,id: 'name_filter'
            ,emptyText: _('filter_by_name')
            ,listeners: {
                'change': {fn:this.filterByName,scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
        }] 
    });
    MODx.grid.PluginEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.PluginEvent,MODx.grid.Grid,{
    filterByName: function(tf,newValue,oldValue) {
        this.getStore().baseParams = {
            action: 'getList'
            ,name: newValue
            ,id: this.config.plugin
        };
        this.getStore().load({
            params: {
                start: 0
                ,limit: 20
            }
            ,scope: this
            ,callback: this.refresh
        });
    }
});
Ext.reg('modx-grid-plugin-event',MODx.grid.PluginEvent);