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
            action: 'system/databasetable/getlist'
        }
        ,fields: ['Name','Rows','Reserved','Data_size','Index_length','Data_free']
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
            header: _('database_table_reserved')
            ,dataIndex: 'Reserved'
            ,width: 70
        },{
            header: _('database_table_datasize')
            ,dataIndex: 'Data_size'
            ,width: 70
        },{
            header: _('database_table_indexsize')
            ,dataIndex: 'Index_length'
            ,width: 70
        },{
            header: _('database_table_unused')
            ,dataIndex: 'Data_free'
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
                action: 'system/databasetable/truncate'
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
                action: 'system/databasetable/optimize'
                ,t: table
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
        return false;
    }
    ,optimizeDatabase: function(table) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'system/databasetable/optimizeDatabase'
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
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
