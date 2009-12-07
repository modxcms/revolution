MODx.grid.ResourceSecurity = function(config) {
    config = config || {};
    var ac = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: false
    });

    Ext.applyIf(config,{
        id: 'modx-grid-resource-security'
        ,url: MODx.config.connectors_url+'resource/resourcegroup.php'
        ,baseParams: {
            action: 'getList'
            ,resource: config.resource
        }
        ,saveParams: {
            resource: config.resource
        }
        ,fields: ['id','name','access','menu']
        ,paging: true
        ,remoteSort: true
        ,plugins: ac
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },ac]
    });
    MODx.grid.ResourceSecurity.superclass.constructor.call(this,config);
    this.on('rowclick',MODx.fireResourceFormChange);
};
Ext.extend(MODx.grid.ResourceSecurity,MODx.grid.Grid);
Ext.reg('modx-grid-resource-security',MODx.grid.ResourceSecurity);