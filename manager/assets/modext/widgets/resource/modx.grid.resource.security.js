/**
 * @class MODx.grid.ResourceSecurity
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-resource-security
 */
MODx.grid.ResourceSecurity = function(config) {
    config = config || {};
    var ac = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: false
        ,hidden: MODx.perm.resourcegroup_resource_edit != 1
    });

    var qs = Ext.urlDecode(window.location.search.substring(1));
    Ext.applyIf(config,{
        id: 'modx-grid-resource-security'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Resource/ResourceGroup/GetList'
            ,resource: config.resource
            ,"parent": config["parent"]
            ,mode: config.mode || 'update'
            ,"token": qs.reload || ''
        }
        ,saveParams: {
            resource: config.resource
        }
        ,fields: ['id','name','access']
        ,paging: true
        ,remoteSort: true
        ,plugins: ac
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,renderer: { fn: function(v,md,record) {
                return this.rendLink(v, {
                    href: '?a=security/resourcegroup'
                    ,target: '_blank'
                });
            }, scope: this }
        },ac]
    });
    MODx.grid.ResourceSecurity.superclass.constructor.call(this,config);
    this.on('rowclick',MODx.fireResourceFormChange);
};
Ext.extend(MODx.grid.ResourceSecurity,MODx.grid.Grid);
Ext.reg('modx-grid-resource-security',MODx.grid.ResourceSecurity);
