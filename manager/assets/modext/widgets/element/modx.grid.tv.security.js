/**
 * Loads a grid of resource groups assigned to a resource. 
 * 
 * @class MODx.grid.TVSecurity
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-tv-security
 */
MODx.grid.TVSecurity = function(config) {
    config = config || {};
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-tv-security'
        ,url: MODx.config.connectors_url+'element/tv/resourcegroup.php'
        ,fields: ['id','name','access','menu']
        ,baseParams: {
            action: 'getList'
            ,tv: config.tv
        }
        ,saveParams: {
            tv: config.tv
        }
        ,width: 800
        ,paging: true
        ,remoteSort: true
        ,plugins: tt
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },tt]
        
    });
    MODx.grid.TVSecurity.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TVSecurity,MODx.grid.Grid);
Ext.reg('modx-grid-tv-security',MODx.grid.TVSecurity);
