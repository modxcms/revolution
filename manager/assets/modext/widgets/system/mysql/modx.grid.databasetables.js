/**
 * Loads a grid of all the database tables in the modx database.
 *
 * @class MODx.grid.DatabaseTables
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-databasetables
 */
MODx.grid.DatabaseTables = function(config) {
    config = config || {};
	Ext.applyIf(config,{
        title: _('database_tables')
        ,id: 'modx-grid-dbtable'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/DatabaseTable/GetList'
        }
        ,fields: ['Name','Rows','Data_size','Data_free','Effective_size','Index_length','Total_size']
        ,showActionsColumn: false
        ,paging: false
        ,columns: [{
            header: _('database_table_tablename')
            ,dataIndex: 'Name'
            ,width: 250
        },{
            header: _('database_table_records')
            ,dataIndex: 'Rows'
            ,width: 70
        },{
            header: _('database_table_datasize')
            ,dataIndex: 'Data_size'
            ,width: 70
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
              if (record.json.canTruncate == true) {
                  return `<a href="javascript:;" onclick="truncate('${record.data.Name}')" title="${_('truncate_table')}">${value}</a>`;
              }
              return value;
           }
        },{
            header: _('database_table_overhead')
            ,dataIndex: 'Data_free'
            ,width: 70
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
              if (record.json.canOptimize == true) {
                  return `<a href="javascript:;" onclick="optimize('${record.data.Name}')" title="${_('optimize_table')}">${value}</a>`;
              }
              return value;
           }
        },{
            header: _('database_table_effectivesize')
            ,dataIndex: 'Effective_size'
            ,width: 70
        },{
            header: _('database_table_indexsize')
            ,dataIndex: 'Index_length'
            ,width: 70
        },{
            header: _('database_table_totalsize')
            ,dataIndex: 'Total_size'
            ,width: 70
        }]
        ,tbar: [{
            text: _('database_optimize')
            ,cls:'primary-button'
            ,handler: this.optimizeDatabase
            ,scope: this
        }]
    });
    MODx.grid.DatabaseTables.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.DatabaseTables,MODx.grid.Grid,{
    /**
     * Truncates the specified SQL table.
     * @param {String} table
     */
    truncate: function(table) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'System/DatabaseTable/Truncate'
                ,t: table
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        return false;
    }
    /**
     * Optimizes the specified SQL table.
     * @param {String} table
     */
    ,optimize: function(table) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'System/DatabaseTable/Optimize'
                ,t: table
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        return false;
    }
    ,optimizeDatabase: function(table) {
        Ext.Msg.show({
            title: _('please_wait')
            ,msg: _('database_optimize_process')
            ,wait: true
            ,waitConfig :
                {
                    interval: 400,
                    text : _('database_optimize_processing'),
                }
            ,width: 240
            ,progress: true
            ,closable: false
        });

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'System/DatabaseTable/OptimizeDatabase'
            }
            ,listeners: {
                'success': {
                    fn: function(r) {
                        this.refresh();
                        Ext.Msg.hide();
                        MODx.msg.status({
                            title: _('success')
                            ,message: _('database_optimize_success')
                        });
                    }
                    ,scope: this
                }
                ,'failure': {
                    fn: function(r) {
                        Ext.Msg.hide();
                        MODx.msg.alert(_('error'),_('database_optimize_error'));
                    }
                    ,scope: this
                }
            }
        });
        return false;
    }
});
Ext.reg('modx-grid-databasetables',MODx.grid.DatabaseTables);

var truncate = function(name) {
    Ext.getCmp('modx-grid-dbtable').truncate(name);
};
var optimize = function(name) {
    Ext.getCmp('modx-grid-dbtable').optimize(name);
};
